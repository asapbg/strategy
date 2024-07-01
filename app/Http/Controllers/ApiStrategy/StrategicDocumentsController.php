<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Enums\AdvisoryTypeEnum;
use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Models\FieldOfAction;
use App\Models\File;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentChildren;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class StrategicDocumentsController extends ApiController
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
                        sd.id as id,
                        sdt.title as name,
                        enums.level_name as level,
                        foat."name" as policy_area,
                        sdtt."name" as strategic_document_type,
                        -- act_type: <string>,
                        -- act_number: <string>,
                        -- act_link: <string>,
                        sd.pris_act_id,
                        case when sd.pris_act_id is not null then
                        (
                            select array_agg(it."name")
                            from pris
                            left join pris_institution pi2 on pi2.pris_id = pris.id
                            left join institution i on i.id = pi2.institution_id
                            left join institution_translations it on it.institution_id = i.id and it.locale = \''.$this->locale.'\'
                            where
                                pris.id = sd.pris_act_id
                                and pi2.institution_id <> '.env('DEFAULT_INSTITUTION_ID',0).'
                            group by pris.id
                        ) else null end as author_institutions,
                        aast."name" as accepting_institution_type,
                        sd.document_date,
                        pc.reg_num as public_consultation_number,
                        sd.active,
                        sd.link_to_monitorstat,
                        sd.document_date_accepted::date as date_accepted,
                        sd.document_date_expiring::date as date_expiring,
                        (
                            select jsonb_agg(jsonb_build_object(\'name\', sdf.description, \'path\', \''.url('/strategy-document/download-file').'\' || \'/\' || sdf.id, \'version\', sdf."version"))
                            from strategic_document_file sdf where sdf.strategic_document_id = sd.id and sdf.locale = \''.$this->locale.'\'
                        ) as files,
                        null as subdocuments
                    from strategic_document sd
                    join strategic_document_translations sdt on sdt.strategic_document_id = sd.id and sdt.locale = \''.$this->locale.'\'
                    left join field_of_actions foa on foa.id = sd.policy_area_id
                    left join field_of_action_translations foat on foat.field_of_action_id = foa.id and foat.locale = \''.$this->locale.'\'
                    left join strategic_document_type sdt2 on sdt2.id = sd.strategic_document_type_id
                    left join strategic_document_type_translations sdtt on sdtt.strategic_document_type_id = sdt2.id and sdtt.locale = \''.$this->locale.'\'
                    left join authority_accepting_strategic aas on aas.id = sd.accept_act_institution_type_id
                    left join authority_accepting_strategic_translations aast on aast.authority_accepting_strategic_id = aas.id and aast.locale = \''.$this->locale.'\'
                    left join public_consultation pc on pc.id = sd.public_consultation_id
                    left join (select level_id, level_name from (
                                    values (1, \''.__('custom.strategic_document.levels.CENTRAL').'\'),
                                    (2, \''.__('custom.strategic_document.levels.AREA').'\'),
                                    (3, \''.__('custom.strategic_document.levels.MUNICIPAL').'\')
                        ) E(level_id, level_name)) enums on enums.level_id = sd.strategic_document_level_id
                    where true
                        '.(!$this->authanticated ? ' and sd.deleted_at is null and sd.active = true ' : '').'
                        '.(isset($from) ? ' and sd.document_date_accepted >= \''.$from.' 00:00:00'.'\'' : '').'
                        '.(isset($to) ? ' and sd.document_date_expiring <= \''.$to.' 23:59:59'.'\'' : '').'
                        '.(isset($types) && !empty($types)? ' and sdt2.id in ('.$types.')': '').'
                    '.($this->request_limit ? ' limit '.$this->request_limit : '').'
                    '.($this->request_offset ? ' offset '.$this->request_offset : '').'
                ');

        $finalData = array();
        if(sizeof($data)){
            foreach ($data as $row){
                if(!empty($row->author_institutions)){
                    $row->author_institutions = json_decode($row->author_institutions, true);
                }
                if(!empty($row->files)){
                    $row->files = json_decode($row->files, true);
                }

                $row->subdocuments = StrategicDocumentChildren::getTreeApi(0, $row->id, true);
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
                        sd.id as id,
                        sdt.title as name,
                        enums.level_name as level,
                        -- foat."name" as policy_area,
                        foa.id as policy_area,
                        -- sdtt."name" as strategic_document_type,
                        sdt.id as strategic_document_type,
                        -- act_type: <string>,
                        -- act_number: <string>,
                        -- act_link: <string>,
                        sd.pris_act_id,
                        case when sd.pris_act_id is not null then
                        (
                            select array_agg(it."name")
                            from pris
                            left join pris_institution pi2 on pi2.pris_id = pris.id
                            left join institution i on i.id = pi2.institution_id
                            left join institution_translations it on it.institution_id = i.id and it.locale = \''.$this->locale.'\'
                            where
                                pris.id = sd.pris_act_id
                                and pi2.institution_id <> '.env('DEFAULT_INSTITUTION_ID',0).'
                            group by pris.id
                        ) else null end as author_institutions,
                        -- aast."name" as accepting_institution_type,
                        aast.id as accepting_institution_type,
                        sd.document_date,
                        pc.reg_num as public_consultation_number,
                        sd.active,
                        sd.link_to_monitorstat,
                        sd.document_date_accepted::date as date_accepted,
                        sd.document_date_expiring::date as date_expiring,
                        (
                            select jsonb_agg(jsonb_build_object(\'id\', sdf.id, \'name\', sdf.description, \'path\', \''.url('/strategy-document/download-file/').'\' || \'/\' || sdf.id, \'version\', sdf."version"))
                            from strategic_document_file sdf where sdf.strategic_document_id = sd.id and sdf.deleted_at is null and sdf.locale = \''.$this->locale.'\'
                        ) as files,
                        null as subdocuments
                    from strategic_document sd
                    join strategic_document_translations sdt on sdt.strategic_document_id = sd.id and sdt.locale = \''.$this->locale.'\'
                    left join field_of_actions foa on foa.id = sd.policy_area_id
                    left join field_of_action_translations foat on foat.field_of_action_id = foa.id and foat.locale = \''.$this->locale.'\'
                    left join strategic_document_type sdt2 on sdt2.id = sd.strategic_document_type_id
                    left join strategic_document_type_translations sdtt on sdtt.strategic_document_type_id = sdt2.id and sdtt.locale = \''.$this->locale.'\'
                    left join authority_accepting_strategic aas on aas.id = sd.accept_act_institution_type_id
                    left join authority_accepting_strategic_translations aast on aast.authority_accepting_strategic_id = aas.id and aast.locale = \''.$this->locale.'\'
                    left join public_consultation pc on pc.id = sd.public_consultation_id
                    left join (select level_id, level_name from (
                                    values (1, \''.__('custom.strategic_document.levels.CENTRAL').'\'),
                                    (2, \''.__('custom.strategic_document.levels.AREA').'\'),
                                    (3, \''.__('custom.strategic_document.levels.MUNICIPAL').'\')
                        ) E(level_id, level_name)) enums on enums.level_id = sd.strategic_document_level_id
                    where true
                        '.(!$this->authanticated ? ' and sd.deleted_at is null ' : '').'
                        and sd.id = '.$id.'
                ');

        if(sizeof($data)){
            $data = $data[0];
            if(!empty($data->author_institutions)){
                $data->author_institutions = json_decode($data->author_institutions, true);
            }
            if(!empty($data->files)){
                $data->files = json_decode($data->files, true);
            }
            $data->subdocuments = StrategicDocumentChildren::getTreeApi(0, $data->id, true);
        }
        if(empty($data)){
            return $this->returnError(Response::HTTP_NOT_FOUND, 'Not found');
        }
        return $this->output($data);
    }

    public function subdocuments(Request $request, int $id = 0)
    {

        $sd = StrategicDocument::find($id);
        if(!$sd){
            return $this->returnError(Response::HTTP_NOT_FOUND, 'Strategic document not found');
        }

        $data = StrategicDocumentChildren::getTreeApi(0, $sd->id, true);
        return $this->output($data);
    }
}
