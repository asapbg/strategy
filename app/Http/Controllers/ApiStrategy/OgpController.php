<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Enums\AdvisoryTypeEnum;
use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Enums\OgpStatusEnum;
use App\Enums\OldNationalPlanEnum;
use App\Enums\PublicationTypesEnum;
use App\Models\FieldOfAction;
use App\Models\File;
use App\Models\OgpStatus;
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
                            max(opt2."content") as text,
                            \'\' as final_version_pdf,
                            op2.from_date as date_start,
                            op2."to_date" as date_end,
                            max(ost.name) as status,
                            (
                                select \'http://strategy.test/download/\' || f.id
                                from files f
                                where true
                                    '.(!$this->authanticated ? ' and f.deleted_at is null ' : '').'
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
                                                where true
                                                    '.(!$this->authanticated ? ' and f2.deleted_at is null and op2.report_evaluation_published_at is not null' : '').'
                                    and f2.id_object = op2.id
                                    and f2.code_object = '.File::CODE_OBJ_OGP.'
                                    and f2.doc_type in ('.DocTypesEnum::OGP_REPORT_EVALUATION->value.')
                                    and f2.locale = \''.$this->locale.'\'
                            ) as reports,
                            (
                                select
                                    jsonb_agg(jsonb_build_object(\'name\', oat."name", \'commitments\',(
                                        select jsonb_agg(jsonb_build_object(\'name\', opat."name", \'context\', opat."content", \'npo_partner\', opat.npo_partner, \'values_initiative\', opat.values_initiative, \'problem\', opat.problem, \'solving_problem\', opat.solving_problem, \'responsible_institution\', opat.responsible_administration, \'evaluation\', opat.evaluation, \'evaluation_status\', opat.evaluation_status, \'stakeholders\', opat.interested_org, \'deadline\', opa2.to_date, \'contacts\', opat.contact_names))
                                        from ogp_plan_arrangement opa2
                                        join ogp_plan_arrangement_translations opat on opat.ogp_plan_arrangement_id = opa2.id and opat.locale = \''.$this->locale.'\'
                                        where true
                                             '.(!$this->authanticated ? ' and opa2.deleted_at is null ' : '').'
                                            and opa2.ogp_plan_area_id = opa.id
                                        )
                                    ))
                                from ogp_plan_area opa
                                left join ogp_area oa2 on oa2.id = opa.ogp_area_id
                                left join ogp_area_translations oat on oat.ogp_area_id = oa2.id and oat.locale = \''.$this->locale.'\'
                                where true
                                     '.(!$this->authanticated ? ' and opa.deleted_at is null ' : '').'
                                    and opa.ogp_plan_id = op2.id
                            ) as areas,
                            (
                                select
                                    jsonb_agg(jsonb_build_object(\'date_from\', ops.start_date , \'date_to\', ops.end_date , \'name\', opst."name"  , \'description\', opst.description))
                                from ogp_plan_schedule ops
                                join ogp_plan_schedule_translations opst on opst.ogp_plan_schedule_id = ops.id and opst.locale = \''.$this->locale.'\'
                                where true
                                    '.(!$this->authanticated ? ' and ops.deleted_at is null ' : '').'
                                    and ops.ogp_plan_id = op2.id
                            ) as events,
                            1 as group_by
                        from ogp_plan op2
                        join ogp_plan_translations opt2 on opt2.ogp_plan_id = op2.id and opt2.locale = \''.$this->locale.'\'
                        join ogp_status os2 on os2.id = op2.ogp_status_id
                        join ogp_status_translations ost on ost.ogp_status_id = os2.id and ost.locale = \''.$this->locale.'\'
                        where true
                            and op2.national_plan = 1
                            and os2."type" in ('.OgpStatusEnum::ACTIVE->value.( $this->authanticated ? ','.OgpStatusEnum::DRAFT->value : '').') -- OgpStatusEnum::ACTIVE->value
                            '.(isset($from) ? ' and op2.from_date >= \''.$from.'\'' : '').'
                            '.(isset($to) ? ' and op2.to_date <= \''.$to.'\'' : '').'
                        group by op2.id
                        '.($this->request_limit ? ' limit '.$this->request_limit : '').'
                        '.($this->request_offset ? ' offset '.$this->request_offset : '').'
	            ');

        $finalData = array();
        if(sizeof($data)){
            foreach ($data as $row){
                $row->final_version_pdf = route('ogp.national_action_plans.export', $row->id);
                if(!empty($row->areas)){
                    $row->areas = json_decode($row->areas, true);
                } else {
                    $row->areas = [];
                }
                if(!empty($row->reports)){
                    $row->reports = json_decode($row->reports, true);
                } else {
                    $row->reports = [];
                }

                if(empty($row->events)){
                    $row->events = [];
                }

                $finalData[] = $row;
            }
        }

        $oldPlans = OldNationalPlanEnum::planData(0, app()->getLocale());
        $oldPlanStatus = OgpStatus::Final()->get()->first();
        if(sizeof($oldPlans)){
            foreach ($oldPlans as $id => $plan){
                $final_version_pdf = '';
                switch ($id){
                    case OldNationalPlanEnum::FIRST->value:
                        $final_version_pdf = asset('files/old_ogp_plan/1/Plan-BG.pdf');
                        break;
                    case OldNationalPlanEnum::SECOND->value:
                        $final_version_pdf = asset('files/old_ogp_plan/2/Втори-план-ПОУ-финал-одобрен-МС.pdf');
                        break;
                    case OldNationalPlanEnum::THIRD->value:
                        $final_version_pdf = asset('files/old_ogp_plan/3/Трети_план_ПОУ-2.pdf');
                        break;
                }
                //id, name, text, final_version_pdf, date_start, date_end, status, version_after_public_consultation_pdf,reports,areas,events,group_by
                $finalData[] = array(
                    'id' => (0 - $id),
                    'name' => OldNationalPlanEnum::nameByValue($id),
                    'text' => $plan['ogDescription'][$this->locale],
                    'final_version_pdf' => $final_version_pdf,
                    'date_start' => OldNationalPlanEnum::fromDateByValue($id),
                    'date_end' => OldNationalPlanEnum::toDateByValue($id),
                    'status' => $oldPlanStatus?->translate($this->locale)->name,
                    'version_after_public_consultation_pdf' => null,
                    'reports' => [],
                    'areas' => [],
                    'events' => []
                );
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
                        max(ost.name) as status,
                        (
                            select \'http://strategy.test/download/\' || f.id
                            from files f
                            where true
                                '.(!$this->authanticated ? ' and f.deleted_at is null ' : '').'
                            and f.id_object = op2.id
                            and f.code_object = '.File::CODE_OBJ_OGP.' --
                            and f.doc_type = '.DocTypesEnum::OGP_VERSION_AFTER_CONSULTATION->value.'
                            and f.locale = \''.$this->locale.'\'
                            order by f.created_at desc
                            limit 1
                        ) as version_after_public_consultation_pdf,
                        (
                            select
                                jsonb_agg(jsonb_build_object(\'id\', oat.id, \'name\', oat."name", \'commitments\',(
                                    select jsonb_agg(jsonb_build_object(\'id\', opa2.id, \'name\', opat."name", \'context\', opat."content", \'npo_partner\', opat.npo_partner, \'values_initiative\', opat.values_initiative, \'problem\', opat.problem, \'solving_problem\', opat.solving_problem, \'responsible_institution\', opat.responsible_administration, \'evaluation\', opat.evaluation, \'evaluation_status\', opat.evaluation_status, \'stakeholders\', opat.interested_org, \'deadline\', opa2.to_date, \'contacts\', opat.contact_names
                                ))
                            from ogp_plan_arrangement opa2
                            join ogp_plan_arrangement_translations opat on opat.ogp_plan_arrangement_id = opa2.id and opat.locale = \''.$this->locale.'\'
                            where true
                                '.(!$this->authanticated ? ' and opa2.deleted_at is null ' : '').'
                                and opa2.ogp_plan_area_id = opa.id
                            )
                            ))
                            from ogp_plan_area opa
                            left join ogp_area oa2 on oa2.id = opa.ogp_area_id
                            left join ogp_area_translations oat on oat.ogp_area_id = oa2.id and oat.locale = \''.$this->locale.'\'
                            where true
                                '.(!$this->authanticated ? ' and opa.deleted_at is null ' : '').'
                                and opa.ogp_plan_id = op2.id
                            ) as areas,
                            (
                                select
                                    jsonb_agg(jsonb_build_object(\'id\', f2.id ,\'name\', f2.description_'.$this->locale.' , \'link\', \'http://strategy.test/download/\' || f2.id))
                                            from files f2
                                            where true
                                                '.(!$this->authanticated ? ' and f2.deleted_at is null and op2.report_evaluation_published_at is not null' : '').'
                                and f2.id_object = op2.id
                                and f2.code_object = '.File::CODE_OBJ_OGP.'
                                and f2.doc_type in ('.DocTypesEnum::OGP_REPORT_EVALUATION->value.')
                                and f2.locale = \''.$this->locale.'\'
                            ) as reports,
                            (
                                select
                                    jsonb_agg(jsonb_build_object(\'id\', ops.id, \'date_from\', ops.start_date , \'date_to\', ops.end_date , \'name\', opst."name"  , \'description\', opst.description))
                                from ogp_plan_schedule ops
                                join ogp_plan_schedule_translations opst on opst.ogp_plan_schedule_id = ops.id and opst.locale = \''.$this->locale.'\'
                                where true
                                    '.(!$this->authanticated ? ' and ops.deleted_at is null ' : '').'
                                    and ops.ogp_plan_id = op2.id
                            ) as events
                    from ogp_plan op2
                    join ogp_plan_translations opt2 on opt2.ogp_plan_id = op2.id and opt2.locale = \''.$this->locale.'\'
                    join ogp_status os2 on os2.id = op2.ogp_status_id
                    join ogp_status_translations ost on ost.ogp_status_id = os2.id and ost.locale = \''.$this->locale.'\'
                    where true
                        '.(!$this->authanticated ? ' and op2.deleted_at is null ' : '').'
                        and op2.national_plan = 1
                        and os2."type" in ('.OgpStatusEnum::ACTIVE->value.($this->authanticated ? ','.OgpStatusEnum::DRAFT->value.','.OgpStatusEnum::IN_DEVELOPMENT->value : '').') -- OgpStatusEnum::ACTIVE->value
                        and op2.id = '.$id.'
                    group by op2.id
                ');

        if(sizeof($data)){
            $data = $data[0];
            if(!empty($data->areas)){
                $data->areas = json_decode($data->areas, true);
            } else {
                $data->areas = [];
            }
            if(!empty($data->reports)){
                $data->reports = json_decode($data->reports, true);
            } else {
                $data->reports = [];
            }

            if(empty($data->events)){
                $data->events = [];
            }
        }
        if(empty($data)){
            return $this->returnError(Response::HTTP_NOT_FOUND, 'Not found');
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
            where true
                '.(!$this->authanticated ? ' and p.active = true and p.deleted_at is null ' : '').'
                and p."type" = '.PublicationTypesEnum::TYPE_OGP_NEWS->value.'
                '.($this->request_limit ? ' limit '.$this->request_limit : '').'
                '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');

        return $this->output($data);
    }

    public function devPlans(Request $request){
        $data = DB::select('
            select A.*
            from (
                select
                    op.id,
                    max(opt.name) as title,
                    max(ost.name) as status,
                    jsonb_agg(jsonb_build_object(\'name\', oat."name", \'date_start\', op.from_date_develop, \'date_end\', op.to_date_develop, \'proposals\',
                        (
                            select jsonb_agg(jsonb_build_object(\'user_name\', u.first_name || \' \' || u.last_name , \'date\', to_char(opao.created_at, \'DD.MM.YYYY\') , \'content\', opao."content" , \'votes_for\', opao.likes_cnt , \'votes_against\', opao.dislikes_cnt, \'comments\',
                                (
                                    select jsonb_agg(jsonb_build_object(\'date\', to_char(opaoc.created_at, \'DD.MM.YYYY\'), \'author_name\', u2.first_name || \'\' || u2.last_name , \'text\', opaoc."content"))
                                    from ogp_plan_area_offer_comment opaoc
                                    left join users u2 on u2.id = opaoc.users_id
                                    where
                                        opaoc.ogp_plan_area_offer_id = opao.id
                                        and opaoc.deleted_at is null
                                )
                            ))
                            from ogp_plan_area_offer opao
                            left join users u on u.id = opao.users_id
                            where
                                opao.deleted_at is null
                                and opao.ogp_plan_area_id = opa.id
                        )
                    )) filter (where opa.id is not null) as areas
                from ogp_plan op
                join ogp_plan_translations opt on opt.ogp_plan_id = op.id and opt.locale = \''.$this->locale.'\'
                join ogp_status os on os.id = op.ogp_status_id
                join ogp_status_translations ost on ost.ogp_status_id = os.id and ost.locale = \''.$this->locale.'\'
                left join ogp_plan_area opa on opa.ogp_plan_id = op.id
                left join ogp_area oa on oa.id = opa.ogp_area_id
                left join ogp_area_translations oat on oat.ogp_area_id = oa.id and oat.locale = \''.$this->locale.'\'
                where
                    op.deleted_at is null
                    and op.national_plan = 0
                    and os."type" in (' . OgpStatusEnum::IN_DEVELOPMENT->value . ')
                    and opa.deleted_at is null
                group by op.id, opa.id
                order by op.id desc
                limit 1
            ) A
        union all
            (
                select
                    op.id,
                    max(opt.name) as title,
                    max(ost.name) as status,
                    jsonb_agg(jsonb_build_object(\'name\', oat."name", \'date_start\', op.from_date_develop, \'date_end\', op.to_date_develop, \'proposals\',
                        (
                            select jsonb_agg(jsonb_build_object(\'user_name\', u.first_name || \' \' || u.last_name , \'date\', to_char(opao.created_at, \'DD.MM.YYYY\') , \'content\', opao."content" , \'votes_for\', opao.likes_cnt , \'votes_against\', opao.dislikes_cnt, \'comments\',
                                (
                                    select jsonb_agg(jsonb_build_object(\'date\', to_char(opaoc.created_at, \'DD.MM.YYYY\'), \'author_name\', u2.first_name || \'\' || u2.last_name , \'text\', opaoc."content"))
                                    from ogp_plan_area_offer_comment opaoc
                                    left join users u2 on u2.id = opaoc.users_id
                                    where
                                        opaoc.ogp_plan_area_offer_id = opao.id
                                        and opaoc.deleted_at is null
                                )
                            ))
                            from ogp_plan_area_offer opao
                            left join users u on u.id = opao.users_id
                            where
                                opao.deleted_at is null
                                and opao.ogp_plan_area_id = opa.id
                        )
                    )) filter (where opa.id is not null) as areas
                from ogp_plan op
                join ogp_plan_translations opt on opt.ogp_plan_id = op.id and opt.locale = \'' . $this->locale . '\'
                join ogp_status os on os.id = op.ogp_status_id
                join ogp_status_translations ost on ost.ogp_status_id = os.id and ost.locale = \''.$this->locale.'\'
                left join ogp_plan_area opa on opa.ogp_plan_id = op.id
                left join ogp_area oa on oa.id = opa.ogp_area_id
                left join ogp_area_translations oat on oat.ogp_area_id = oa.id and oat.locale = \'' . $this->locale . '\'
                where
                    op.deleted_at is null
                    and op.national_plan = 0
                    and (os."type" = ' . OgpStatusEnum::FINAL->value . ' ' . ($this->authanticated ? 'or os."type" = ' . OgpStatusEnum::DRAFT->value : '') . ')
                    and opa.deleted_at is null
                group by op.id, opa.id
                order by op.id desc
            )

            ' . ($this->request_limit ? ' limit ' . $this->request_limit : '') . '
            ' . ($this->request_offset ? ' offset ' . $this->request_offset : '') . '
        ');

        if(sizeof($data)){
            foreach ($data as $row){
                if(!empty($row->areas)){
                    $row->areas = json_decode($row->areas, true);
                    if(!empty($row->areas)){
                        foreach ($row->areas as $ka => $va){
                            if(!empty($va['proposals'])){
                                foreach ($va['proposals'] as $kp => $vp){
                                    if(empty($vp['comments'])){
                                        $row->areas[$ka]['proposals'][$kp]['comments'] = [];
                                    }
                                }
                            } else {
                                $row->areas[$ka]['proposals'] = [];
                            }
                        }
                    }
                }
                else {
                    $row->areas = [];
                }
            }
        }

        return $this->output($data);
    }
}
