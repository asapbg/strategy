<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Enums\AdvisoryTypeEnum;
use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Enums\OgpStatusEnum;
use App\Enums\PublicationTypesEnum;
use App\Models\FieldOfAction;
use App\Models\File;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentChildren;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OgpController extends ApiController
{
    public function list(Request $request){
        if(isset($this->request_inputs['type']) && !empty($this->request_inputs['type'])){
            $types = $this->request_inputs['type'];
        }

        if(isset($this->request_inputs['date-after']) && !empty($this->request_inputs['date-after'])){
            if(!$this->checkDate($this->request_inputs['date-after'])){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'date-after\'');
            }
            $from = Carbon::parse($this->request_inputs['date-after'])->format('Y-m-d');
        }

        if(isset($this->request_inputs['date-before']) && !empty($this->request_inputs['date-before'])){
            if(!$this->checkDate($this->request_inputs['date-before'])){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'date-before\'');
            }
            $to = Carbon::parse($this->request_inputs['date-before'])->format('Y-m-d');
        }

        $data = DB::select('
                        select
                            op2.id as id,
                            max(opt2."name") as name,
                            op2.from_date as date_start,
                            op2."to_date" as date_end,
                            (
                                select \'http://strategy.test/download/\' || f.id
                                from files f
                                where
                                    f.deleted_at is null
                                and f.id_object = op2.id
                                and f.code_object = '.File::CODE_OBJ_OGP.' --
                                and f.doc_type = '.DocTypesEnum::OGP_VERSION_AFTER_CONSULTATION->value.'
                                and f.locale = \''.$this->locale.'\'
                                order by f.created_at desc
                                limit 1
                            ) as version_after_public_consultation_pdf,
                            (
                                    select
                                        jsonb_agg(jsonb_build_object(\'name\', f2.description_'.$this->locale.' , \'link\', \'http://strategy.test/download/\' || f2.id))
                                                from files f2
                                                where
                                                    f2.deleted_at is null
                                    and f2.id_object = op2.id
                                    and f2.code_object = '.File::CODE_OBJ_OGP.'
                                    and f2.doc_type in ('.DocTypesEnum::OGP_REPORT_EVALUATION->value.')
                                    and f2.locale = \''.$this->locale.'\'
                            ) as other_files,
                            (
                                select
                                    jsonb_agg(jsonb_build_object(\'name\', oat."name", \'commitments\',(
                                        select jsonb_agg(jsonb_build_object(\'name\', opat."name", \'context\', opat."content", \'npo_partner\', opat.npo_partner, \'values_initiative\', opat.values_initiative, \'problem\', opat.problem, \'solving_problem\', opat.solving_problem, \'responsible_institution\', opat.responsible_administration, \'evaluation\', opat.evaluation, \'evaluation_status\', opat.evaluation_status, \'stakeholders\', opat.interested_org, \'deadline\', opa2.to_date, \'contacts\', opat.contact_names
                                    ))
                                from ogp_plan_arrangement opa2
                                join ogp_plan_arrangement_translations opat on opat.ogp_plan_arrangement_id = opa2.id and opat.locale = \''.$this->locale.'\'
                                where
                                    opa2.deleted_at is null
                                    and opa2.ogp_plan_area_id = opa.id
                                )
                                ))
                                from ogp_plan_area opa
                                left join ogp_area oa2 on oa2.id = opa.ogp_area_id
                                left join ogp_area_translations oat on oat.ogp_area_id = oa2.id and oat.locale = \''.$this->locale.'\'
                                where
                                    opa.ogp_plan_id = op2.id
                                    and opa.deleted_at is null
                                ) as areas,
                                \'??\' as reports,
                                (
                                    select
                                        jsonb_agg(jsonb_build_object(\'date_from\', ops.start_date , \'date_to\', ops.end_date , \'name\', opst."name"  , \'description\', opst.description))
                                    from ogp_plan_schedule ops
                                    join ogp_plan_schedule_translations opst on opst.ogp_plan_schedule_id = ops.id and opst.locale = \''.$this->locale.'\'
                                    where
                                        ops.deleted_at is null
                                        and ops.ogp_plan_id = op2.id
                                ) as events,
                                1 as group_by
                        from ogp_plan op2
                        join ogp_plan_translations opt2 on opt2.ogp_plan_id = op2.id and opt2.locale = \''.$this->locale.'\'
                        join ogp_status os2 on os2.id = op2.ogp_status_id
                        where
                            op2.deleted_at is null
                            and op2.national_plan = 1
                            and os2."type" in ('.OgpStatusEnum::ACTIVE->value.') -- OgpStatusEnum::ACTIVE->value
                            '.(isset($from) ? ' and op2.from_date >= \''.$from.'\'' : '').'
                            '.(isset($to) ? ' and op2.to_date <= \''.$to.'\'' : '').'
                        group by op2.id
                        '.($this->request_limit ? ' limit '.$this->request_limit : '').'
                        '.($this->request_offset ? ' offset '.$this->request_offset : '').'
	            ');

        $finalData = array();
        if(sizeof($data)){
            foreach ($data as $row){
                if(!empty($row->other_files)){
                    $row->other_files = json_decode($row->other_files, true);
                }
                if(!empty($row->areas)){
                    $row->areas = json_decode($row->areas, true);
                }
                $finalData[] = $row;
            }
        }
        $data = $finalData;
        return $this->output($data);

    }

    public function show(Request $request, $id = 0)
    {
        $data = DB::select('
                    select
                        op2.id as id,
                        max(opt2."name") as name,
                        op2.from_date as date_start,
                        op2."to_date" as date_end,
                        (
                            select \'http://strategy.test/download/\' || f.id
                            from files f
                            where
                                f.deleted_at is null
                            and f.id_object = op2.id
                            and f.code_object = '.File::CODE_OBJ_OGP.' --
                            and f.doc_type = '.DocTypesEnum::OGP_VERSION_AFTER_CONSULTATION->value.'
                            and f.locale = \''.$this->locale.'\'
                            order by f.created_at desc
                            limit 1
                        ) as version_after_public_consultation_pdf,
                        (
                                select
                                    jsonb_agg(jsonb_build_object(\'id\', f2.id ,\'name\', f2.description_'.$this->locale.' , \'link\', \'http://strategy.test/download/\' || f2.id))
                                            from files f2
                                            where
                                                f2.deleted_at is null
                                and f2.id_object = op2.id
                                and f2.code_object = '.File::CODE_OBJ_OGP.'
                                and f2.doc_type in ('.DocTypesEnum::OGP_REPORT_EVALUATION->value.')
                                and f2.locale = \''.$this->locale.'\'
                        ) as other_files,
                        (
                            select
                                jsonb_agg(jsonb_build_object(\'id\', oat.id, \'name\', oat."name", \'commitments\',(
                                    select jsonb_agg(jsonb_build_object(\'id\', opa2.id, \'name\', opat."name", \'context\', opat."content", \'npo_partner\', opat.npo_partner, \'values_initiative\', opat.values_initiative, \'problem\', opat.problem, \'solving_problem\', opat.solving_problem, \'responsible_institution\', opat.responsible_administration, \'evaluation\', opat.evaluation, \'evaluation_status\', opat.evaluation_status, \'stakeholders\', opat.interested_org, \'deadline\', opa2.to_date, \'contacts\', opat.contact_names
                                ))
                            from ogp_plan_arrangement opa2
                            join ogp_plan_arrangement_translations opat on opat.ogp_plan_arrangement_id = opa2.id and opat.locale = \''.$this->locale.'\'
                            where
                                opa2.deleted_at is null
                                and opa2.ogp_plan_area_id = opa.id
                            )
                            ))
                            from ogp_plan_area opa
                            left join ogp_area oa2 on oa2.id = opa.ogp_area_id
                            left join ogp_area_translations oat on oat.ogp_area_id = oa2.id and oat.locale = \''.$this->locale.'\'
                            where
                                opa.ogp_plan_id = op2.id
                                and opa.deleted_at is null
                            ) as areas,
                            \'??\' as reports,
                            (
                                select
                                    jsonb_agg(jsonb_build_object(\'id\', ops.id, \'date_from\', ops.start_date , \'date_to\', ops.end_date , \'name\', opst."name"  , \'description\', opst.description))
                                from ogp_plan_schedule ops
                                join ogp_plan_schedule_translations opst on opst.ogp_plan_schedule_id = ops.id and opst.locale = \''.$this->locale.'\'
                                where
                                    ops.deleted_at is null
                                    and ops.ogp_plan_id = op2.id
                            ) as events
                    from ogp_plan op2
                    join ogp_plan_translations opt2 on opt2.ogp_plan_id = op2.id and opt2.locale = \''.$this->locale.'\'
                    join ogp_status os2 on os2.id = op2.ogp_status_id
                    where
                        op2.deleted_at is null
                        and op2.national_plan = 1
                        and os2."type" in ('.OgpStatusEnum::ACTIVE->value.') -- OgpStatusEnum::ACTIVE->value
                        and op2.id = '.$id.'
                    group by op2.id
                ');

        if(sizeof($data)){
            $data = $data[0];
            if(!empty($data->other_files)){
                $data->other_files = json_decode($data->other_files, true);
            }
            if(!empty($data->areas)){
                $data->areas = json_decode($data->areas, true);
            }
        }
        return $this->output($data);
    }

    public function news(Request $request)
    {
        $data = DB::select('
            select
                p.id,
                to_char(p.published_at, \'DD.MM.YYYY\') as date,
                pt.title as title,
                pt."content" as content
            from "publication" p
            left join publication_translations pt on pt.publication_id = p.id and pt.locale = \'bg\'
            where
                p.active = true
                and p."type" = '.PublicationTypesEnum::TYPE_OGP_NEWS->value.'
                and p.deleted_at is null
                '.($this->request_limit ? ' limit '.$this->request_limit : '').'
                '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');

        return $this->output($data);
    }
}
