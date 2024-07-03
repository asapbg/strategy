<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Enums\AdvisoryTypeEnum;
use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Http\Requests\StoreStrategicDocumentApiRequest;
use App\Http\Requests\StrategicDocumentChildStoreApiRequest;
use App\Models\AuthorityAcceptingStrategic;
use App\Models\FieldOfAction;
use App\Models\File;
use App\Models\Pris;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentChildren;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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
                            select array_agg(it."name") filter (where it.id is not null)
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
                                    values ('.InstitutionCategoryLevelEnum::CENTRAL->value.', \'' . __('custom.strategic_document.levels.CENTRAL') . '\'),
                                    ('.InstitutionCategoryLevelEnum::AREA->value.', \'' . __('custom.strategic_document.levels.AREA') . '\'),
                                    ('.InstitutionCategoryLevelEnum::MUNICIPAL->value.', \'' . __('custom.strategic_document.levels.MUNICIPAL') . '\')
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
                            select array_agg(it."name") filter (where it.id is not null)
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
                                    values ('.InstitutionCategoryLevelEnum::CENTRAL->value.', \'' . __('custom.strategic_document.levels.CENTRAL') . '\'),
                                    ('.InstitutionCategoryLevelEnum::AREA->value.', \'' . __('custom.strategic_document.levels.AREA') . '\'),
                                    ('.InstitutionCategoryLevelEnum::MUNICIPAL->value.', \'' . __('custom.strategic_document.levels.MUNICIPAL') . '\')
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

    public function create(Request $request)
    {
        Log::channel('strategy_api')->info('Create strategic document method. Inputs:'.json_encode($this->request_inputs, JSON_UNESCAPED_UNICODE));
        $rs = new StoreStrategicDocumentApiRequest();
        $validator = Validator::make($this->request_inputs, $rs->rules());
        if($validator->fails()){
            return $this->returnErrors(Response::HTTP_OK, $validator->errors()->toArray());
        }

        $validated = $validator->validated();

        if(isset($validated['document_date']) && !$this->checkDate($validated['document_date']) && $validated['accept_act_institution_type_id'] != AuthorityAcceptingStrategic::COUNCIL_MINISTERS){
            return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'document_date\'');
        }

        if(isset($validated['document_date_accepted']) && !$this->checkDate($validated['document_date_accepted']) && $validated['accept_act_institution_type_id'] != AuthorityAcceptingStrategic::COUNCIL_MINISTERS ){
            return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'document_date_accepted\'');
        }

        if(isset($validated['document_date_expiring']) && !$this->checkDate($validated['document_date_expiring']) && (!isset($validated['date_expiring_indefinite']) || !$validated['date_expiring_indefinite']) && $validated['accept_act_institution_type_id'] != AuthorityAcceptingStrategic::COUNCIL_MINISTERS){
            return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'document_date_expiring\'');
        }

        DB::beginTransaction();
        try {
            if(isset($validated['date_expiring_indefinite']) && $validated['date_expiring_indefinite']){
                $validated['document_date_expiring'] = null;
            }
            $validated['parent_document_id'] = $validated['connected_document_id'] ?? null;
            if( $validated['accept_act_institution_type_id'] == AuthorityAcceptingStrategic::COUNCIL_MINISTERS ) {
                $validated['strategic_act_number'] = null;
                $validated['strategic_act_link'] = null;
                $validated['document_date'] = null;

                $prisActId = Arr::get($validated, 'pris_act_id');
                $validated['document_date_accepted'] = $prisActId ? Pris::find($prisActId)->doc_date : ($validated['document_date_accepted'] ?? Carbon::now());
                $datesToBeParsedToCarbon = [
                    'document_date_accepted',
                    'document_date_expiring',
                    'document_date',
                ];

                foreach ($datesToBeParsedToCarbon as $date) {
                    if (array_key_exists($date, $validated)) {
                        $validated[$date] = $validated[$date] ? Carbon::parse($validated[$date]) : null;
                    }
                }
            } else {
                $validated['pris_act_id'] = null;
            }
            $item = new StrategicDocument();

            $fillable = $this->getFillableValidated($validated, $item);

            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(StrategicDocument::TRANSLATABLE_FIELDS, $item, $validated);

            DB::commit();

            return $this->output(['id' => $item->id]);
        } catch (\Exception $e) {

            Log::error($e);
            DB::rollBack();
            return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, __('messages.system_error'));
        }
    }

    public function createSubDocument(Request $request)
    {
        Log::channel('strategy_api')->info('Create strategic sub document method. Inputs:'.json_encode($this->request_inputs, JSON_UNESCAPED_UNICODE));
        $rs = new StrategicDocumentChildStoreApiRequest();
        $validator = Validator::make($this->request_inputs, $rs->rules());
        if($validator->fails()){
            return $this->returnErrors(Response::HTTP_OK, $validator->errors()->toArray());
        }

        $validated = $validator->validated();

        if(isset($validated['document_date_accepted']) && !$this->checkDate($validated['document_date_accepted']) && !isset($validated['pris_act_id']) ){
            return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'document_date_accepted\'');
        }

        if(isset($validated['document_date_expiring']) && !$this->checkDate($validated['document_date_expiring']) && (!isset($validated['date_expiring_indefinite']) || !$validated['date_expiring_indefinite'])){
            return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'document_date_expiring\'');
        }

        $sd = StrategicDocument::find((int)$validated['sd_id']);
        if(!$sd){
            return $this->returnError(Response::HTTP_NOT_FOUND, 'Стратегическият документ не съществува');
        }
        $validated['doc'] = $validated['sub_doc_parent'] ?? null;
        $validated['sd'] = $validated['sd_id'] ?? null;
        $validated['strategic_document_level_id'] = $sd->strategic_document_level_id;

        DB::beginTransaction();
        try {
            $validated['document_date_accepted'] = isset($validated['pris_act_id']) ? Pris::find($validated['pris_act_id'])->doc_date : ($validated['document_date_accepted'] ?? Carbon::now());
            $item = new StrategicDocumentChildren();
            $fillable = $this->getFillableValidated($validated, $item);
            $fillable['strategic_document_id'] = $sd->id;
            $fillable['parent_id'] = $validated['doc'] ?? null;
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(StrategicDocumentChildren::TRANSLATABLE_FIELDS, $item, $validated);
            DB::commit();

            return $this->output(['id' => $item->id]);
        } catch (\Exception $e) {

            Log::error($e);
            DB::rollBack();
            return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, __('messages.system_error'));
        }
    }
}
