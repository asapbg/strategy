<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PublicConsultationsController extends ApiController
{
    public function list(Request $request){

        if(isset($this->request_inputs['active'])){
            $active = (int)$this->request_inputs['active'];
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
                pc.reg_num,
                pc.consultation_level_id as "level",
                pc.open_from as date_open,
                pc.open_to as date_close,
                pc.active,
                pc.field_of_actions_id
            from public_consultation pc
            where
                pc.deleted_at is null
                '.(isset($active) ? ' and pc.active = '.$active : '').'
                '.(isset($actTypeIds) ? ' and pc.act_type_id in ('.$actTypeIds.')' : '').'
                '.(isset($policyAreasIds) ? ' and pc.field_of_actions_id in ('.$policyAreasIds.')' : '').'
                '.(isset($institutionsIds) ? ' and pc.importer_institution_id in ('.$institutionsIds.')' : '').'
                '.(isset($from) ? ' and pc.open_from >= \''.$from.'\'' : '').'
                '.(isset($to) ? ' and pc.open_to <= \''.$to.'\'' : '').'
            '.($this->request_limit ? ' limit '.$this->request_limit : '').'
            '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');

        return $this->output($data);
    }

    public function show(Request $request, $id = 0)
    {
        $defaultInstitution = env('DEFAULT_INSTITUTION_ID');
        $data = DB::select(
            'select
                        pc.id,
                        pc.reg_num,
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
                        (pc.active::int)::bool as active,
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
                            select jsonb_agg(jsonb_build_object(\'date\', c.created_at::date , \'author_name\', u2.first_name || \' \' || u2.middle_name || \' \' || u2.last_name , \'text\', c."content"))
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
                        ) as files
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
                                and cf.deleted_at is null
                    where
                        pc.deleted_at is null
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
                }
        }
        return $this->output($data);
    }

    public function comments(Request $request, int $id = 0)
    {
        $data = DB::select('
            select
                c.id,
                c.created_at::date as date,
                u2.first_name || \' \' || u2.middle_name || \' \' || u2.last_name as author_name,
                c."content" as text
                from comments c
                left join users u2 on u2.id = c.user_id
                left join public_consultation pc on pc.id = c.object_id
                where
                    c.object_id = '.$id.'
                    and pc.deleted_at is null
                    and c.object_code = 1 -- model Comments::PC_OBJ_CODE
                    and c.deleted_at is null
        ');

        return $this->output($data);
    }
}
