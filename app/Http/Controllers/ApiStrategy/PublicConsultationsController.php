<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Http\Requests\StorePublicConsultationApiRequest;
use App\Models\ActType;
use App\Models\Consultations\LegislativeProgram;
use App\Models\Consultations\OperationalProgram;
use App\Models\Consultations\PublicConsultation;
use App\Models\File;
use App\Models\StrategicDocuments\Institution;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PublicConsultationsController extends ApiController
{
    public function list(Request $request){

        if(isset($this->request_inputs['active'])){
            $active = (int)$this->request_inputs['active'];
        }
        if(isset($this->request_inputs['published'])){
            $published = (int)$this->request_inputs['published'];
        }

        if(isset($this->request_inputs['act-type']) && !empty($this->request_inputs['act-type'])){
            $actTypeIds = $this->request_inputs['act-type'];
        }

        if(isset($this->request_inputs['policy-area']) && !empty($this->request_inputs['policy-area'])){
            $policyAreasIds = $this->request_inputs['policy-area'];
        }

        if(isset($this->request_inputs['author']) && !empty($this->request_inputs['author'])){
            $institutionsIds = $this->request_inputs['author'];
        }

        if(isset($this->request_inputs['date-open-after']) && !empty($this->request_inputs['date-open-after'])){
            if(!$this->checkDate($this->request_inputs['date-open-after'])){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'date-open-after\'');
            }
            $from = Carbon::parse($this->request_inputs['date-open-after'])->format('Y-m-d');
        }

        if(isset($this->request_inputs['date-close-before']) && !empty($this->request_inputs['date-close-before'])){
            if(!$this->checkDate($this->request_inputs['date-close-before'])){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'date-close-before\'');
            }
            $to = Carbon::parse($this->request_inputs['date-close-before'])->format('Y-m-d');
        }

        $data = DB::select('
            select
                pc.id,
                pct.title as name,
                pct.description,
                pc.reg_num,
                pc.act_type_id,
                pc.consultation_level_id as "level",
                pc.open_from as date_open,
                pc.open_to as date_close,
                pc.active as published,
                pc.legislative_program_id,
                pc.operational_program_id,
                case when NOW()::date >= pc.open_from and NOW()::date <= pc.open_to then true else false end as active,
                pc.field_of_actions_id as policy_area,
                (
                    select jsonb_agg(poll.id)
                    from public_consultation_poll
                    join poll on poll.id = public_consultation_poll.poll_id
                    where
                        poll.deleted_at is null
                        and public_consultation_poll.public_consultation_id = pc.id
                ) as polls
            from public_consultation pc
            join public_consultation_translations pct on pct.public_consultation_id = pc.id and pct.locale = \''.$this->locale.'\'
            where true
                '.(!$this->authanticated ? ' and pc.deleted_at is null and pc.active = 1 ' : '').'
                '.(isset($active) ? ($active ? ' and (NOW()::date >= pc.open_from and NOW()::date <= pc.open_to)' : ' and (NOW()::date <= pc.open_from or NOW()::date >= pc.open_to)') : '').'
                '.(isset($published) ? ' and pc.active = '.$published  : '').'
                '.(isset($actTypeIds) ? ' and pc.act_type_id in ('.$actTypeIds.')' : '').'
                '.(isset($policyAreasIds) ? ' and pc.field_of_actions_id in ('.$policyAreasIds.')' : '').'
                '.(isset($institutionsIds) ? ' and pc.importer_institution_id in ('.$institutionsIds.')' : '').'
                '.(isset($from) ? ' and pc.open_from >= \''.$from.'\'' : '').'
                '.(isset($to) ? ' and pc.open_to <= \''.$to.'\'' : '').'
            '.($this->request_limit ? ' limit '.$this->request_limit : '').'
            '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');

        $finalData = array();
        if(sizeof($data)){
            foreach ($data as $row){
                if(!empty($row->polls)){
                    $row->polls = json_decode($row->polls, true);
                } else{
                    $row->polls = [];
                }
                $finalData[] = $row;
            }
        }
        $data = $finalData;

        return $this->output($data);
    }

    public function show(Request $request, $id = 0)
    {
        $defaultInstitution = env('DEFAULT_INSTITUTION_ID');
        $data = DB::select(
            'select
                        pc.id,
                        pct.title as name,
                        pct.description,
                        pc.reg_num,
                        pc.act_type_id,
                        -- pc.consultation_level_id as consultation_level,
                        case when pc.consultation_level_id = '.InstitutionCategoryLevelEnum::CENTRAL->value.'
                        then \'Централно\'
                        else (
                            case when pc.consultation_level_id = '.InstitutionCategoryLevelEnum::CENTRAL_OTHER->value.'
                            then \'Централно друго\'
                            else (
                                case when pc.consultation_level_id = '.InstitutionCategoryLevelEnum::AREA->value.'
                                then \'Областно\'
                                else (
                                    case when pc.consultation_level_id = '.InstitutionCategoryLevelEnum::MUNICIPAL->value.'
                                    then \'Общинско\'
                                    else
                                        \'\'
                                    end
                                )
                                end
                            )
                            end
                        )
                        end as consultation_type,
                        att.name as act_type,
                        pc.open_from as date_open,
                        pc.open_to as date_close,
                        pct.short_term_reason,
                        (pc.active::int)::bool as published,
                        case when NOW()::date >= pc.open_from and NOW()::date <= pc.open_to then true else false end as active,
                        foa.id as policy_area_id,
                        foat.name as policy_area,
                        pc.legislative_program_id,
                        pc.operational_program_id,
                        pc.responsible_institution_id,
                        it.name as responsible_institution_name,
                        it.address as responsible_institution_address,
                        pct.proposal_ways,
                        (
                            select jsonb_agg(jsonb_build_object(\'name\', pcc.name, \'email\', pcc.email))
                            from public_consultation_contact pcc
                            where
                                pcc.public_consultation_id = pc.id
                                and pcc.deleted_at is null
                        ) as contacts,
                        lt.name as law_name,
                        pc.law_id,
                        pc.pris_id,
                        u.first_name || \' \' || u.middle_name || \' \' || u.last_name as author_name,
                        (
                            select jsonb_agg(jsonb_build_object(\'date\', c.created_at::date , \'author_name\', (case when u2.id is not null then u2.first_name || \' \' || u2.last_name else \'\' end) , \'text\', c."content"))
                            from comments c
                            left join users u2 on u2.id = c.user_id
                            where
                                c.object_id = pc.id
                                and c.object_code = 1 -- model Comments::PC_OBJ_CODE
                                and c.deleted_at is null
                        ) as comments,
                        (
                            select jsonb_build_object(\'structure\', (
                                select jsonb_agg(jsonb_build_object(\'column\', A.column_name , \'value\', A.column_value))
                                from (
                                    select
                                        max(dsct.label) as column_name,
                                        cdr.value as column_value
                                    from consultation_document cd
                                    join consultation_document_row cdr on cdr.consultation_document_id = cd.id and cdr.deleted_at is null
                                    join dynamic_structure_column dsc on dsc.id = cdr.dynamic_structures_column_id and dsc.deleted_at is null
                                    join dynamic_structure_column_translations dsct on dsct.dynamic_structure_column_id = dsc.id and dsct.locale = \''.$this->locale.'\'
                                    where
                                        cd.public_consultation_id = pc.id
                                        and cd.deleted_at is null
                                    group by cdr.id
                                    order by max(dsc.ord)
                                ) A
                            ), \'file\', case when cf.id is not null then \''.url('/').'\' || \'/download/\' || cf.id else \'\' end)
                        ) as consultation_document,
                        (
                            select jsonb_agg(jsonb_build_object(\'type\', A.type_name, \'versions\', A.files))
                            from (
                                select f.doc_type, case when max(enums.type_name) is not null then max(enums.type_name) else \''.__('validation.attributes.other').'\' end as type_name, jsonb_agg(jsonb_build_object(\'date\', f.created_at::date, \'link\', \''.url('/').'\' || \'/download/\' || f.id)) as files
                                from files f
                                left join (select type_id, type_name from (
                                            values ('.DocTypesEnum::PC_IMPACT_EVALUATION->value.', \''.__('custom.public_consultation.doc_type.'.DocTypesEnum::PC_IMPACT_EVALUATION->value).'\'),
                                            ('.DocTypesEnum::PC_IMPACT_EVALUATION_OPINION->value.', \''.__('custom.public_consultation.doc_type.'.DocTypesEnum::PC_IMPACT_EVALUATION_OPINION->value).'\'),
                                            ('.DocTypesEnum::PC_REPORT->value.', \''.__('custom.public_consultation.doc_type.'.DocTypesEnum::PC_REPORT->value).'\'),
                                            ('.DocTypesEnum::PC_DRAFT_ACT->value.', \''.__('custom.public_consultation.doc_type.'.DocTypesEnum::PC_DRAFT_ACT->value).'\'),
                                            ('.DocTypesEnum::PC_MOTIVES->value.', \''.__('custom.public_consultation.doc_type.'.DocTypesEnum::PC_MOTIVES->value).'\'),
                                            ('.DocTypesEnum::PC_CONSOLIDATED_ACT_VERSION->value.', \''.__('custom.public_consultation.doc_type.'.DocTypesEnum::PC_CONSOLIDATED_ACT_VERSION->value).'\'),
                                            ('.DocTypesEnum::PC_OTHER_DOCUMENTS->value.', \''.__('custom.public_consultation.doc_type.'.DocTypesEnum::PC_OTHER_DOCUMENTS->value).'\'),
                                            ('.DocTypesEnum::PC_COMMENTS_REPORT->value.', \''.__('custom.public_consultation.doc_type.'.DocTypesEnum::PC_COMMENTS_REPORT->value).'\'),
                                            ('.DocTypesEnum::PC_COMMENTS_CSV->value.', \''.__('custom.public_consultation.doc_type.'.DocTypesEnum::PC_COMMENTS_CSV->value).'\'),
                                            ('.DocTypesEnum::PC_COMMENTS_PDF->value.', \''.__('custom.public_consultation.doc_type.'.DocTypesEnum::PC_COMMENTS_PDF->value).'\'),
                                            ('.DocTypesEnum::PC_KD_PDF->value.', \''.__('custom.public_consultation.doc_type.'.DocTypesEnum::PC_KD_PDF->value).'\'),
                                            ('.DocTypesEnum::PC_POLLS_PDF->value.', \''.__('custom.public_consultation.doc_type.'.DocTypesEnum::PC_POLLS_PDF->value).'\')
                                ) E(type_id, type_name)) enums on enums.type_id = f.doc_type
                                where
                                    f.id_object = pc.id
                                    and f.deleted_at is null
                                        and f.locale = \''.$this->locale.'\'
                                        and f.code_object = '.File::CODE_OBJ_PUBLIC_CONSULTATION.'
                                group by f.doc_type
                                order by f.doc_type
                            ) A
                        ) as files,
                        (
                            select jsonb_agg(poll.id)
                            from public_consultation_poll
                            join poll on poll.id = public_consultation_poll.poll_id
                            where
                                poll.deleted_at is null
                                and public_consultation_poll.public_consultation_id = pc.id
                        ) as polls
                    from public_consultation pc
                    join public_consultation_translations pct on pct.public_consultation_id = pc.id and pct.locale = \''.$this->locale.'\'
                    left join field_of_actions foa on foa.id = pc.field_of_actions_id
                    left join field_of_action_translations foat on foat.field_of_action_id = foa.id and foat.locale = \''.$this->locale.'\'
                    left join act_type at2 on at2.id = pc.act_type_id
                    left join act_type_translations att on att.act_type_id = at2.id and att.locale = \''.$this->locale.'\'
                    left join law l on l.id = pc.law_id
                    left join law_translations lt on lt.law_id = l.id and lt.locale = \''.$this->locale.'\'
                    left join institution i on i.id = pc.responsible_institution_id  and i.id <> '.$defaultInstitution.' --
                    left join institution_translations it on it.institution_id = i.id and it.locale = \''.$this->locale.'\'
                    left join users u on u.id = pc.user_id
                    left join files cf on cf.id_object = pc.id
                                and cf.code_object = '.File::CODE_OBJ_PUBLIC_CONSULTATION.'
                                and cf.doc_type = '.DocTypesEnum::PC_KD_PDF->value.'
                                and cf.locale = \''.$this->locale.'\'
                        '.(!$this->authanticated ? ' and cf.deleted_at is null ' : '').'
                    where true
                        '.(!$this->authanticated ? ' and pc.deleted_at is null ' : '').'
                        and pc.id = '.$id.'
            '
        );

        if(sizeof($data)){
            $data = $data[0];
                if(!empty($data->contacts)){
                    $data->contacts = json_decode($data->contacts, true);
                }
                if(!empty($data->comments)){
                    $data->comments = json_decode($data->comments, true);
                }
                if(!empty($data->consultation_document)){
                    $data->consultation_document = json_decode($data->consultation_document, true);
                }
                if(!empty($data->files)){
                    $data->files = json_decode($data->files, true);
                } else {
                    $data->files = [];
                }
                if(!empty($data->polls)){
                    $data->polls = json_decode($data->polls, true);
                } else {
                    $data->polls = [];
                }
        }
        if(empty($data)){
            return $this->returnError(Response::HTTP_NOT_FOUND, 'Not found');
        }
        return $this->output($data);
    }

    public function comments(Request $request, int $id = 0)
    {
        $data = DB::select('
            select
                c.id,
                c.created_at::date as date,
                case when u2.id is not null then u2.first_name || \' \' || u2.last_name else \'\' end as author_name,
                c."content" as text
                from comments c
                left join users u2 on u2.id = c.user_id
                left join public_consultation pc on pc.id = c.object_id
                where true
                    '.(!$this->authanticated ? ' and pc.deleted_at is null and c.deleted_at is null ' : '').'
                    and c.object_id = '.$id.'
                    and c.object_code = 1 -- model Comments::PC_OBJ_CODE
        ');

        return $this->output($data);
    }

    public function create(Request $request)
    {
        Log::channel('strategy_api')->info('Create public consultation method. Inputs:'.json_encode($this->request_inputs, JSON_UNESCAPED_UNICODE));
        $rs = new StorePublicConsultationApiRequest();
        $validator = Validator::make($this->request_inputs, $rs->rules());
        if($validator->fails()){
            return $this->returnErrors(Response::HTTP_OK, $validator->errors()->toArray());
        }

        $validated = $validator->validated();

        if(!$this->checkDate($validated['open_from'])){
            return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'open_from\'');
        }

        if(!$this->checkDate($validated['open_to'])){
            return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'open_to\'');
        }

        if(Carbon::parse($validated['open_from'])->format('Y-m-d') > Carbon::parse($validated['open_to'])->format('Y-m-d')){
            return $this->returnErrors(Response::HTTP_OK, ['open_to' => 'Крайната дата трябва да е след началната']);
        }

        //Some custom validations
        $institution = Institution::find((int)$validated['institution_id']);
        $institutionLevel = $institution && $institution->level ? $institution->level->nomenclature_level : 0;
        $actType = ActType::find((int)$validated['act_type_id']);
        $actTypeLevel = $actType ? $actType->consultation_level_id : 0;
        if($institutionLevel != $actTypeLevel){
            return $this->returnErrors(Response::HTTP_OK, ['act_type_id' => 'Нивото на \'Вид акт\' не съвпада с нивото на институцията']);
        }

        if(isset($validated['field_of_actions_id']) && !in_array($validated['field_of_actions_id'], $institution->fieldsOfAction->pluck('id')->toArray())){
            return $this->returnErrors(Response::HTTP_OK, ['field_of_actions_id' => 'Областта на политика не е асоциирана с избраната институция']);
        }

        $minDuration = PublicConsultation::MIN_DURATION_DAYS;
        $hortDuration = PublicConsultation::SHORT_DURATION_DAYS;
        $from = $validated['open_from'] ? Carbon::parse($validated['open_from']) : null;
        $to = $validated['open_to'] ? Carbon::parse($validated['open_to']) : null;
        $diffDays = $to && $from ? $to->diffInDays($from) : null;

        if( $diffDays && $diffDays < $minDuration) {
            return $this->returnErrors(Response::HTTP_OK, ['open_to' => 'Минималната продължителност е '.$minDuration.' дни']);
        }

        if( $diffDays && $diffDays <= $hortDuration ) {
            if(!isset($validated['short_term_reason_bg']) || empty(isset($validated['short_term_reason_bg']))){
                return $this->returnErrors(Response::HTTP_OK, ['short_term_reason_bg' => 'Моля да посочите \'Причина за кратък срок\'']);
            }
        } else{
            if(isset($validated['short_term_reason_bg'])){
                unset($validated['short_term_reason_bg']);
            }
            if(isset($validated['short_term_reason_en'])){
                unset($validated['short_term_reason_en']);
            }
        }

        //Consultation level
        $centralConsultationLevel = \App\Enums\InstitutionCategoryLevelEnum::CENTRAL->value;
        //Acts
        $actLaw = \App\Models\ActType::ACT_LAW;
        $actMinistry = \App\Models\ActType::ACT_COUNCIL_OF_MINISTERS;
        if($institution->level->nomenclature_level == $centralConsultationLevel){
            if(isset($validated['act_type_id']) && $validated['act_type_id'] == $actLaw){
                foreach (['operational_program_id', 'operational_program_row_id', 'law_id', 'pris_id'] as $field){
                    $validated[$field] = null;
                }
            } elseif (isset($validated['act_type_id']) && $validated['act_type_id'] == $actMinistry){
                foreach (['legislative_program_id', 'legislative_program_row_id', 'law_id', 'pris_id'] as $field){
                    $validated[$field] = null;
                }
            } else{
                foreach (['operational_program_id', 'operational_program_row_id', 'legislative_program_id', 'legislative_program_row_id', 'law_id', 'pris_id'] as $field){
                    $validated[$field] = null;
                }
            }
        } else{
            foreach (['operational_program_id', 'operational_program_row_id', 'legislative_program_id', 'legislative_program_row_id', 'law_id', 'pris_id'] as $field){
                $validated[$field] = null;
            }
        }
        $validated['operational_program_row_id'] = isset($validated['operational_program_row_id']) && (int)$validated['operational_program_row_id'] > 0 ? $validated['operational_program_row_id'] : null;
        $validated['legislative_program_row_id'] = isset($validated['legislative_program_row_id']) && (int)$validated['legislative_program_row_id'] > 0 ? $validated['legislative_program_row_id'] : null;
        $validated['pris_id'] = isset($validated['pris_id']) && $validated['pris_id'] > 0 ? $validated['pris_id'] : null;
        $validated['law_id'] = isset($validated['law_id']) && $validated['law_id'] > 0 ? $validated['law_id'] : null;

        DB::beginTransaction();
        try {
            $item = new PublicConsultation();

            $fillable = $this->getFillableValidated($validated, $item);
            $fillable['consultation_level_id'] = $institution ? $institution->level->nomenclature_level : 0;
            $item->fill($fillable);
            $item->active = $request->filled('active') ? $request->input('active') : 0;
            $item->importer_institution_id = $institution ? $institution->id : null;
            $item->responsible_institution_id = $institution ? $institution->id : null;
            $item->active_in_days = $diffDays;
            $item->save();

            $this->storeTranslateOrNew(PublicConsultation::TRANSLATABLE_FIELDS, $item, $validated);

            $item->consultations()->sync($validated['connected_pc'] ?? []);

            $item->reg_num = $item->id.'-K';
            $item->save();

            //Locke program if is selected
            if( isset($validated['legislative_program_id']) ) {
                LegislativeProgram::where('id', '=', (int)$validated['legislative_program_id'])
                    ->where('locked', '=', 0)
                    ->update(['locked' => 1, 'public_consultation_id' => $item->id]);
            }
            if( isset($validated['operational_program_id']) ) {
                OperationalProgram::where('id', '=', (int)$validated['operational_program_id'])
                    ->where('locked', '=', 0)
                    ->update(['locked' => 1, 'public_consultation_id' => $item->id]);
            }

            DB::commit();

            return $this->output(['id' => $item->id]);
        } catch (\Exception $e) {

            Log::error($e);
            DB::rollBack();
            return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, __('messages.system_error'));
        }
    }
}
