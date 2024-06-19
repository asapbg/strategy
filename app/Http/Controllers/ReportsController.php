<?php

namespace App\Http\Controllers;

use App\Enums\AdvisoryTypeEnum;
use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Enums\PrisDocChangeTypeEnum;
use App\Enums\PublicationTypesEnum;
use App\Models\ActType;
use App\Models\AdvisoryBoard;
use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
use App\Models\File;
use App\Models\LegalActType;
use App\Models\StrategicDocument;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function apiReportSd(Request $request, string $type){
        switch ($type)
        {
            case 'standard':
                $data = DB::select('
                    select
                        sdt.title as name,
                        enums.level_name as level,
                        foat.name as field_of_action,
                        aast."name" as authority,
                        to_char(strategic_document.document_date_accepted, \'DD.MM.YYYY\') || \' - \' || case when strategic_document.document_date_expiring is not null then to_char(strategic_document.document_date_expiring, \'DD.MM.YYYY\') else \''.__('custom.unlimited').'\' end as validity
                    from strategic_document
                    left join field_of_actions foa on foa.id = strategic_document.policy_area_id
                    left join field_of_action_translations foat on foat.field_of_action_id = foa.id and foat.locale = \'bg\'
                    join strategic_document_translations sdt on sdt.strategic_document_id = strategic_document.id and sdt.locale = \'bg\'
                    left join authority_accepting_strategic aas on aas.id = strategic_document.accept_act_institution_type_id
                    left join authority_accepting_strategic_translations aast on aast.authority_accepting_strategic_id = aas.id and aast.locale = \'bg\'
                    left join (select level_id, level_name from (
                                    values (1, \''.__('custom.strategic_document.levels.CENTRAL').'\'),
                                    (2, \''.__('custom.strategic_document.levels.AREA').'\'),
                                    (3, \''.__('custom.strategic_document.levels.MUNICIPAL').'\')
                        ) E(level_id, level_name)) enums on enums.level_id = strategic_document.strategic_document_level_id
                    where
                        strategic_document.active = true
                        and strategic_document.deleted_at is null
                        and strategic_document.parent_document_id is null
                ');

                $header = [
                    'name' => __('custom.title'),
                    'level' => __('site.strategic_document.level'),
                    'field_of_action' => trans_choice('custom.field_of_actions', 1),
                    'authority' => trans_choice('custom.authority_accepting_strategic', 1),
                    'validity' => __('custom.validity')
                ];

                $finalData = array();
                if(sizeof($data)){
                    foreach ($data as $row){
                        $finalData[] = $row;
                    }
                }
                array_unshift($finalData, $header);
                $data = $finalData;
                break;

            case 'full':
                $data = DB::select('
                    select
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
                            left join institution_translations it on it.institution_id = i.id and it.locale = \'bg\'
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
                            select jsonb_agg(jsonb_build_object(\'name\', sdf.description, \'path\', \''.url('/strategy-document/download-file').'\' || sdf.id, \'version\', sdf."version"))
                            from strategic_document_file sdf where sdf.strategic_document_id = sd.id and sdf.locale = \'bg\'
                        ) as files,
                        null as subdocuments
                    from strategic_document sd
                    join strategic_document_translations sdt on sdt.strategic_document_id = sd.id and sdt.locale = \'bg\'
                    left join field_of_actions foa on foa.id = sd.policy_area_id
                    left join field_of_action_translations foat on foat.field_of_action_id = foa.id and foat.locale = \'bg\'
                    left join strategic_document_type sdt2 on sdt2.id = sd.strategic_document_type_id
                    left join strategic_document_type_translations sdtt on sdtt.strategic_document_type_id = sdt2.id and sdtt.locale = \'bg\'
                    left join authority_accepting_strategic aas on aas.id = sd.accept_act_institution_type_id
                    left join authority_accepting_strategic_translations aast on aast.authority_accepting_strategic_id = aas.id and aast.locale = \'bg\'
                    left join public_consultation pc on pc.id = sd.public_consultation_id
                    left join (select level_id, level_name from (
                                    values (1, \''.__('custom.strategic_document.levels.CENTRAL').'\'),
                                    (2, \''.__('custom.strategic_document.levels.AREA').'\'),
                                    (3, \''.__('custom.strategic_document.levels.MUNICIPAL').'\')
                        ) E(level_id, level_name)) enums on enums.level_id = sd.strategic_document_level_id
                    where
                        sd.deleted_at is null
                                and sd.active = true
                ');
                $header = [
                    'title' => __('custom.title'),
                    'level' => capitalize(__('custom.level_lower_case')),
                    'policy_area' => trans_choice('custom.field_of_actions', 1),
                    'strategic_document_type' => trans_choice('custom.strategic_document_type', 1),
//                    'act_type' => '',
//                    'act_number' => '',
//                    'act_link' => '',
                    'pris_act_id' => trans_choice('custom.acts_pris', 1),
                    'author_institution' => trans_choice('custom.importers', 2),
                    'accepting_institution_type' => trans_choice('custom.authority_accepting_strategics', 1),
                    'document_date' => __('custom.document_act'),
                    'public_consultation_number' => trans_choice('custom.public_consultations', 1),
                    'active' => __('custom.status' ),
                    'link_to_monitorstat' => __('validation.attributes.link_to_monitorstat' ),
                    'date_accepted' => __('custom.date_accepted' ),
                    'date_expiring' => __('custom.date_expiring' ),
                    'files' => trans_choice('custom.files', 2 ),
                    'subdocuments' => trans_choice('custom.strategic_documents.documents', 2 ),
                ];

                $finalData = array();
                if(sizeof($data)){
                    foreach ($data as $row){
                        $finalData[] = $row;
                    }
                }
                array_unshift($finalData, $header);
                $data = $finalData;
                break;
            default:
                $data = [];
        }

        return response()->json($data, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function apiReportLegislativeInitiative(Request $request, string $type){
        switch ($type)
        {
            case 'standard':
                $q = DB::table('legislative_initiative')
                    ->select([
                        DB::raw('\''.__('custom.change_f').' '.__('custom.in').'\' || \' \' || max(law_translations.name) as name'),
                        DB::raw('case when max(users.id) is not null then max(users.first_name) || \' \' || max(users.last_name) else \'\' end as author_name'),
                        DB::raw('legislative_initiative.created_at::date as date_open'),
                        DB::raw('legislative_initiative.active_support::date as date_close'),
                        DB::raw('case when legislative_initiative.status::int = '.LegislativeInitiativeStatusesEnum::STATUS_SEND->value.'
                        then \''.__('custom.legislative_' . \Illuminate\Support\Str::lower(LegislativeInitiativeStatusesEnum::STATUS_SEND->name)).'\'
                        else (
                            case when legislative_initiative.status::int = '.LegislativeInitiativeStatusesEnum::STATUS_CLOSED->value.'
                            then \''.__('custom.legislative_' . \Illuminate\Support\Str::lower(LegislativeInitiativeStatusesEnum::STATUS_CLOSED->name)).'\'
                            else \''.__('custom.legislative_' . \Illuminate\Support\Str::lower(LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->name)).'\' end
                        ) end as status'),
                        DB::raw('legislative_initiative.description as description'),
                        DB::raw('max(law_translations.name) as law_name'),
                        DB::raw('legislative_initiative.law_paragraph as law_paragraph'),
                        DB::raw('legislative_initiative.law_text as law_text_change'),
                        DB::raw('legislative_initiative.motivation as motivation'),
                        'legislative_initiative.cap',
                        DB::raw('case when max(ric.id) is not null then true else false end as sent'),
                        DB::raw('sum(case when legislative_initiative_votes.is_like = true then 1 else 0 end) as votes_for'),
                        DB::raw('sum(case when legislative_initiative_votes.is_like = false then 1 else 0 end) as votes_against'),
                        DB::raw('json_agg(ic_translation.name) filter (where ic_translation.name is not null) as institutions'),
                        DB::raw('(
                            select
                                    json_agg(json_build_object(\'date\', date_part(\'year\', legislative_initiative_comments.created_at), \'author_name\', (case when u.id is not null then u.first_name || \' \' || u.last_name else \'\' end), \'text\', legislative_initiative_comments.description))
                                 from legislative_initiative_comments
                                 join users as u on u.id = legislative_initiative_comments.user_id
                                 where
                                    legislative_initiative_comments.legislative_initiative_id = legislative_initiative.id
                                    and legislative_initiative_comments.deleted_at is null
                             ) as comments'),

                    ])
                    ->leftJoin('users', 'users.id', '=', 'legislative_initiative.author_id')
                    ->join('law', 'law.id', '=', 'legislative_initiative.law_id')
                    ->join('law_translations', function ($q){
                        $q->on('law_translations.law_id', '=', 'law.id')->where('law_translations.locale', '=', 'bg');
                    })
                    ->leftJoin('legislative_initiative_votes', 'legislative_initiative_id', '=', 'legislative_initiative.id')
                    ->join('legislative_initiative_institution as ic', 'ic.legislative_initiative_id', '=', 'legislative_initiative.id')
                    ->join('institution as ici', 'ici.id', '=', 'ic.institution_id')
                    ->join('institution_translations as ic_translation', function ($q){
                        $q->on('ic_translation.institution_id', '=', 'ici.id')->where('ic_translation.locale', '=', 'bg');
                    })
                    ->leftJoin('legislative_initiative_receiver as ric', 'ric.legislative_initiative_id', '=', 'legislative_initiative.id')
                    ->leftJoin('institution as rici', 'rici.id', '=', 'ric.institution_id')
                    ->leftJoin('institution_translations as ric_translation', function ($q){
                        $q->on('ric_translation.institution_id', '=', 'rici.id')->where('ric_translation.locale', '=', 'bg');
                    })
                    ->whereNull('legislative_initiative.deleted_at')
                    ->groupBy('legislative_initiative.id')
                    ->orderBy('legislative_initiative.created_at', 'desc');

                $data = $q->get()->map(function ($row) {
                    if(!empty($row->comments)){
                        $row->comments = json_decode($row->comments, true);
                    }
                    return (array)$row;
                })->toArray();

                $header = [
                    'name' => __('custom.name'),
                    'author_name' => __('custom.author'),
                    'date_open' => __('custom.begin_date'),
                    'date_close' => __('site.li_end_support_date'),
                    'status' => __('custom.status'),
                    'description' => __('custom.description_of_suggested_change'),
                    'law_name' => trans_choice('custom.laws', 1),
                    'law_paragraph' => __('validation.attributes.law_paragraph'),
                    'law_text_change' => __('validation.attributes.law_text'),
                    'motivation' => __('validation.attributes.motivation'),
                    'cap' => __('site.li_required_likes'),
                    'sent' => __('custom.sent_date'),
                    'votes_for' => __('custom.likes'),
                    'votes_against' => __('custom.li_dislikes'),
                    'institutions' => trans_choice('custom.institutions', 2),
                    'comments' => trans_choice('custom.comments', 2),
                ];
                array_unshift($data, $header);

                break;
            default:
                $data = [];
        }

        return response()->json($data, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function apiReportLp(Request $request, string $type)
    {
        switch ($type)
        {
            case 'standard':
                $data = DB::select(
                    'select
                                C.legislative_program_id as id,
                                max(C.date_from) as date_from,
                                max(C.date_to) as date_to,
                                (
                                    select jsonb_agg(\''.url('/').'\' || \'/download/\' || f.id)
                                    from files f
                                        where
                                            f.id_object = C.legislative_program_id
                                            and f.deleted_at is null
                                        and f.locale = \'bg\'
                                        and f.code_object = '.File::CODE_OBJ_LEGISLATIVE_PROGRAM_GENERAL.'
                                ) as files,
                                jsonb_agg(jsonb_build_object(C.month, C.records)) as program
                            from (
                                select
                                    B.legislative_program_id,
                                    max(B.date_from) as date_from,
                                    max(B.date_to) as date_to,
                                    B.month,
                                    jsonb_agg(B.records) as records
                                from (
                                    select
                                        A.month,
                                        A.row_num,
                                        jsonb_object_agg(A."label", case when A.col = '.config('lp_op_programs.lp_ds_col_institution_id').' then A.institutions else A.value end) as records,
                                        A.legislative_program_id,
                                        max(A.from_date) as date_from,
                                        max(A.to_date) as date_to
                                    from (
                                        select
                                            lprm.month,
                                            lprm.row_num,
                                            max(dsct."label") as label,
				                            max(lprm.value) as value,
				                            string_agg(it."name", \', \') as institutions,
				                            lprm.dynamic_structures_column_id as col,
                                            lprm.legislative_program_id,
                                            max(lp.from_date) as from_date,
				                            max(lp.to_date) as to_date
                                        from legislative_program_row lprm
                                        join dynamic_structure_column dsc on dsc.id = lprm.dynamic_structures_column_id
                                        join dynamic_structure_column_translations dsct on dsct.dynamic_structure_column_id = dsc.id and dsct.locale = \'bg\'
                                        join legislative_program lp on lp.id = lprm.legislative_program_id and lp.deleted_at is null and lp.public = 1
                                        left join legislative_program_row_institution lpri on lpri.legislative_program_row_id = lprm.id
			                            left join institution_translations it on it.institution_id = lpri.institution_id and it.locale = \'bg\'
                                        where
                                            lprm.deleted_at is null
                                        group by lprm.id, lprm."month", lprm.row_num
                                        order by lprm."month", lprm.row_num, lprm.dynamic_structures_column_id
                                    ) A
                                    group by A.legislative_program_id, A."month", A.row_num
                                    order by A.legislative_program_id, A."month", A.row_num
                                ) B
                                group by B.legislative_program_id, B.month
                            ) C
                            group by C.legislative_program_id
                    '
                );

                $header = [
                    'id' => trans_choice('custom.legislative_program', 1).' (ID)',
                    'date_from' => __('validation.attributes.open_from'),
                    'date_to' => __('validation.attributes.open_to'),
                    'files' => trans_choice('custom.files', 1),
                    'program' => __('custom.program'),
                ];

                $finalData = array();
                if(sizeof($data)){
                    foreach ($data as $row){
                        if(!empty($row->program)){
                            $row->program = json_decode($row->program, true);
                        }
                        $finalData[] = $row;
                    }
                }
                array_unshift($finalData, $header);
                $data = $finalData;
                break;
            default:
                $data = [];
        }
        return response()->json($data, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function apiReportOp(Request $request, string $type)
    {
        switch ($type)
        {
            case 'standard':
                $data = DB::select(
                    'select
                                C.operational_program_id as id,
                                max(C.date_from) as date_from,
                                max(C.date_to) as date_to,
                                (
                                    select jsonb_agg(\''.url('/').'\' || \'/download/\' || f.id)
                                    from files f
                                        where
                                            f.id_object = C.operational_program_id
                                            and f.deleted_at is null
                                        and f.locale = \'bg\'
                                        and f.code_object = '.File::CODE_OBJ_OPERATIONAL_PROGRAM_GENERAL.'
                                ) as files,
                                jsonb_agg(jsonb_build_object(C.month, C.records)) as program
                            from (
                                select
                                    B.operational_program_id,
                                    max(B.date_from) as date_from,
                                    max(B.date_to) as date_to,
                                    B.month,
                                    jsonb_agg(B.records) as records
                                from (
                                    select
                                        A.month,
                                        A.row_num,
                                        jsonb_object_agg(A."label", case when A.col = '.config('lp_op_programs.op_ds_col_institution_id').' then A.institutions else A.value end) as records,
                                        A.operational_program_id,
                                        max(A.from_date) as date_from,
                                        max(A.to_date) as date_to
                                    from (
                                        select
                                            oprm.month,
                                            oprm.row_num,
                                            max(dsct."label") as label,
                                            max(oprm.value) as value,
                                            string_agg(it."name", \', \')  as institutions,
				                            oprm.dynamic_structures_column_id as col,
                                            oprm.operational_program_id,
                                            max(op.from_date) as from_date,
                                            max(op.to_date) as to_date
                                        from operational_program_row oprm
                                        join dynamic_structure_column dsc on dsc.id = oprm.dynamic_structures_column_id
                                        join dynamic_structure_column_translations dsct on dsct.dynamic_structure_column_id = dsc.id and dsct.locale = \'bg\'
                                        join operational_program op on op.id = oprm.operational_program_id and op.deleted_at is null and op.public = 1
                                        left join operational_program_row_institution opri on opri.operational_program_row_id = oprm.id
			                            left join institution_translations it on it.institution_id = opri.institution_id and it.locale = \'bg\'
                                        where
                                            oprm.deleted_at is null
                                        group by oprm.id, oprm."month", oprm.row_num
                                        order by oprm."month", oprm.row_num, oprm.dynamic_structures_column_id
                                    ) A
                                    group by A.operational_program_id, A."month", A.row_num
                                    order by A.operational_program_id, A."month", A.row_num
                                ) B
                                group by B.operational_program_id, B.month
                            ) C
                            group by C.operational_program_id
                    '
                );

                $header = [
                    'id' => trans_choice('custom.legislative_program', 1).' (ID)',
                    'date_from' => __('validation.attributes.open_from'),
                    'date_to' => __('validation.attributes.open_to'),
                    'files' => trans_choice('custom.files', 1),
                    'program' => __('custom.program'),
                ];

                $finalData = array();
                if(sizeof($data)){
                    foreach ($data as $row){
                        if(!empty($row->program)){
                            $row->program = json_decode($row->program, true);
                        }
                        $finalData[] = $row;
                    }
                }
                array_unshift($finalData, $header);
                $data = $finalData;
                break;
            default:
                $data = [];
        }
        return response()->json($data, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }
    public function apiReportPc(Request $request, string $type)
    {
        switch ($type)
        {
            case 'standard':
                $q = DB::table('public_consultation')
                    ->select([
//                        'public_consultation.id',
                        DB::raw('max(public_consultation_translations.title) as title'),
                        DB::raw('max(field_of_action_translations.name) as field_of_action_name'),
                        DB::raw('case when NOW() >= public_consultation.open_from and public_consultation.open_to <= NOW() then \'Активна\' else \'Неактивна\' end as status'),
                        DB::raw('case when max(public_consultation.importer_institution_id) = '.env('DEFAULT_INSTITUTION_ID').' then \'\' else max(institution_translations.name) end as institution_name'),
                        DB::raw('max(act_type_translations.name) as act_type_name'),
                        'public_consultation.act_type_id',
                        'public_consultation.active_in_days',
                        DB::raw('max(public_consultation_translations.short_term_reason) as short_term_reason'),
                        DB::raw('sum(case when comments.id is not null then 1 else 0 end) as comments'),
                        DB::raw('case when max(proposal_report.id) > 0 then \'Да\' else \'Не\' end as has_proposal_report'),
                        DB::raw('json_agg(distinct(files.doc_type)) filter (where files.doc_type is not null) as doc_types')
                    ])
                    ->leftJoin('institution', 'institution.id', '=', 'public_consultation.importer_institution_id')
                    ->leftjoin('institution_translations', function ($j){
                        $j->on('institution_translations.institution_id', '=', 'institution.id')
                            ->where('institution_translations.locale', '=', 'bg');
                    })
                    ->join('public_consultation_translations', function ($j){
                        $j->on('public_consultation_translations.public_consultation_id', '=', 'public_consultation.id')
                            ->where('public_consultation_translations.locale', '=', 'bg');
                    })
                    ->leftjoin('act_type', 'act_type.id', '=', 'public_consultation.act_type_id')
                    ->leftjoin('act_type_translations', function ($j){
                        $j->on('act_type_translations.act_type_id', '=', 'act_type.id')
                            ->where('act_type_translations.locale', '=', 'bg');
                    })
                    ->join('field_of_actions', 'field_of_actions.id', '=', 'public_consultation.field_of_actions_id')
                    ->join('field_of_action_translations', function ($j){
                        $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                            ->where('field_of_action_translations.locale', '=', 'bg');
                    })
                    ->leftjoin('comments', function ($j){
                        $j->on('comments.object_id', '=', 'public_consultation.id')
                            ->where('comments.object_code', '=', Comments::PC_OBJ_CODE);
                    })
                    ->leftjoin('files', function ($j){
                        $j->on('files.id_object', '=', 'public_consultation.id')
                            ->where('files.code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
                            ->where('files.locale', '=', 'bg')
                            ->whereIn('files.doc_type', DocTypesEnum::pcDocTypes());
                    })
                    ->leftjoin('files as proposal_report', function ($j){
                        $j->on('proposal_report.id_object', '=', 'public_consultation.id')
                            ->where('proposal_report.code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
                            ->where('proposal_report.locale', '=', 'bg')
                            ->where('proposal_report.doc_type', DocTypesEnum::PC_COMMENTS_REPORT->value);
                    })
                    ->whereNull('public_consultation.deleted_at')
                    ->where('public_consultation.active', 1)
                    ->groupBy('public_consultation.id');

                    $data = $q->get()->map(fn ($row) => (array)$row)->toArray();

                    if(sizeof($data)){
                        foreach ($data as $item){
                            $existDocTypes = json_decode($item['doc_types']);
                            $actType = $item['act_type_id'];
                            unset($item['doc_types'],$item['act_type_id']);

                            $item['missing_documents'] = '';
                            $requiredDocs = \App\Enums\DocTypesEnum::pcRequiredDocTypesByActType($actType);
                            if(sizeof($requiredDocs)){
                                foreach ($requiredDocs as $rd){
                                    if(empty($existDocTypes) || !in_array($rd, $existDocTypes)){
                                        $item['missing_documents'].= ' '.__('custom.public_consultation.doc_type.'.$rd).';';
                                    }
                                }
                            }
                            $data[] = $item;
                        }
                    }

                $header = [
                    'title' => __('custom.name'),
                    'field_of_action_name' => trans_choice('custom.field_of_actions', 1),
                    'status' => trans_choice('custom.field_of_actions', 1),
                    'institution_name' => trans_choice('custom.institution', 1),
                    'act_type_name' => trans_choice('custom.act_type', 1),
                    'active_in_days' => 'Срок (дни)',
                    'short_term_reason' => __('site.public_consultation.short_term_motive_label'),
                    'comments' => trans_choice('custom.comment', 2),
                    'has_proposal_report' => trans_choice('custom.field_of_actions', 1),
                    'missing_documents' => __('custom.pc_reports.missing_documents'),
                ];
                array_unshift($data, $header);

                break;
            case 'field-of-actions':
                $q = DB::table('field_of_actions')
                    ->select(['field_of_action_translations.name', DB::raw('count(distinct(public_consultation.id)) as pc_cnt')])
                    ->join('field_of_action_translations', function ($j){
                        $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                            ->where('field_of_action_translations.locale', '=', 'bg');
                    })
                    ->leftJoin('public_consultation', function ($q){
                        $q->on('public_consultation.field_of_actions_id', '=', 'field_of_actions.id')
                            ->whereNull('public_consultation.deleted_at');
                    })
                    ->where('field_of_actions.active', '=', 1)
                    ->whereNull('field_of_actions.deleted_at')
                    ->where('field_of_actions.parentid', '<>', 0)
                    ->orderByRaw('max(field_of_actions.parentid)')
                    ->orderBy('field_of_action_translations.name')
                    ->groupBy('field_of_action_translations.id');

                $data = $q->get()->map(fn ($row) => (array)$row)->toArray();
                $header = [
                    'name' => trans_choice('custom.field_of_actions', 1),
                    'pc_cnt' => trans_choice('custom.public_consultations', 2)
                ];
                array_unshift($data, $header);
                break;
            case 'field-of-actions-institution':
                $q = DB::table('public_consultation')
                    ->select(['institution_translations.name', DB::raw('count(distinct(public_consultation.id)) as pc_cnt')])
                    ->join('field_of_actions', function ($q){
                        $q->on('public_consultation.field_of_actions_id', '=', 'field_of_actions.id');
                    })
                    ->join('institution', 'institution.id', '=', 'public_consultation.importer_institution_id')
                    ->join('institution_translations', function ($j){
                        $j->on('institution_translations.institution_id', '=', 'institution.id')
                            ->where('institution_translations.locale', '=', 'bg');
                    })
                    ->where('field_of_actions.active', '=', 1)
                    ->whereNull('field_of_actions.deleted_at')
                    ->whereNull('public_consultation.deleted_at')
                    ->where('public_consultation.active', '=', 1)
                    ->where('institution.id', '<>', env('DEFAULT_INSTITUTION_ID'))
                    ->groupBy('institution_translations.id');

                    $data = $q->get()->map(fn ($row) => (array)$row)->toArray();
                    $header = [
                        'name' => trans_choice('custom.institutions', 1),
                        'pc_cnt' => trans_choice('custom.public_consultations', 2)
                    ];
                    array_unshift($data, $header);
                break;
            case 'institutions':
                //Липса на документ
                $missingFiles = array();
                $qMissingFiles = DB::select('
                    select
                        A.institution_id,
                        sum(A.missing_files) as missing_files
                    from (
                        select
                            public_consultation.id,
                            public_consultation.importer_institution_id as institution_id,
                            -- files.id
                            case when (
                                (public_consultation.act_type_id in ('.ActType::ACT_LAW.','.ActType::ACT_COUNCIL_OF_MINISTERS.') and count(files.id) < 9)
                                or (public_consultation.act_type_id in ('.ActType::ACT_MINISTER.','.ActType::ACT_OTHER_CENTRAL_AUTHORITY.','.ActType::ACT_REGIONAL_GOVERNOR.','.ActType::ACT_MUNICIPAL.','.ActType::ACT_MUNICIPAL_MAYOR.') and count(files.id) < 6)
                                or (public_consultation.act_type_id not in ('.ActType::ACT_LAW.','.ActType::ACT_COUNCIL_OF_MINISTERS.','.ActType::ACT_MINISTER.','.ActType::ACT_OTHER_CENTRAL_AUTHORITY.','.ActType::ACT_REGIONAL_GOVERNOR.','.ActType::ACT_MUNICIPAL.','.ActType::ACT_MUNICIPAL_MAYOR.') and count(files.id) < 3)
                            ) then 1 else 0 end as missing_files
                        from public_consultation
                        left join files on files.id_object = public_consultation.id
                            and files.code_object = 6
                            and files.deleted_at is null
                            and (
                                (public_consultation.act_type_id in ('.ActType::ACT_LAW.','.ActType::ACT_COUNCIL_OF_MINISTERS.') and files.doc_type in ('.DocTypesEnum::PC_DRAFT_ACT->value.','.DocTypesEnum::PC_REPORT->value.','.DocTypesEnum::PC_MOTIVES->value.','.DocTypesEnum::PC_OTHER_DOCUMENTS->value.','.DocTypesEnum::PC_IMPACT_EVALUATION->value.','.DocTypesEnum::PC_IMPACT_EVALUATION_OPINION->value.','.DocTypesEnum::PC_CONSOLIDATED_ACT_VERSION->value.','.DocTypesEnum::PC_KD_PDF->value.','.DocTypesEnum::PC_COMMENTS_REPORT->value.'))
                                or (public_consultation.act_type_id in ('.ActType::ACT_MINISTER.','.ActType::ACT_OTHER_CENTRAL_AUTHORITY.','.ActType::ACT_REGIONAL_GOVERNOR.','.ActType::ACT_MUNICIPAL.','.ActType::ACT_MUNICIPAL_MAYOR.') and files.doc_type in ('.DocTypesEnum::PC_DRAFT_ACT->value.','.DocTypesEnum::PC_MOTIVES->value.','.DocTypesEnum::PC_OTHER_DOCUMENTS->value.','.DocTypesEnum::PC_CONSOLIDATED_ACT_VERSION->value.','.DocTypesEnum::PC_KD_PDF->value.','.DocTypesEnum::PC_COMMENTS_REPORT->value.'))
                                or (public_consultation.act_type_id not in ('.ActType::ACT_LAW.','.ActType::ACT_COUNCIL_OF_MINISTERS.','.ActType::ACT_MINISTER.','.ActType::ACT_OTHER_CENTRAL_AUTHORITY.','.ActType::ACT_REGIONAL_GOVERNOR.','.ActType::ACT_MUNICIPAL.','.ActType::ACT_MUNICIPAL_MAYOR.') and files.doc_type in ('.DocTypesEnum::PC_DRAFT_ACT->value.','.DocTypesEnum::PC_OTHER_DOCUMENTS->value.','.DocTypesEnum::PC_COMMENTS_REPORT->value.'))
                            )
                        where public_consultation.old_id is null
                        group by public_consultation.id
                    ) A
                    group by A.institution_id
                ');
                if(sizeof($qMissingFiles)){
                    $missingFiles = array_combine(array_column($qMissingFiles, 'institution_id'), array_column($qMissingFiles, 'missing_files'));
                }


                $q = DB::table('institution')
                    ->select([
                        'institution.id',
                        DB::raw('max(institution_translations.name) as name'),
                        DB::raw('count(distinct(public_consultation.id)) as pc_cnt'),
                        DB::raw('sum(case when (open_to - open_from) < 30 then 1 else 0 end) as less_days_cnt'),
                        DB::raw('sum(case when (open_to - open_from) < 30 and (public_consultation_translations.short_term_reason is null or public_consultation_translations.short_term_reason = \'\') then 1 else 0 end) as no_less_days_reason_cnt'),
                        DB::raw('count(distinct(files.id)) as has_report')
                    ])
                    ->join('institution_translations', function ($j){
                        $j->on('institution_translations.institution_id', '=', 'institution.id')
                            ->where('institution_translations.locale', '=', app()->getLocale());
                    })
                    ->join('public_consultation', 'institution.id', '=', 'public_consultation.importer_institution_id')
                    ->join('public_consultation_translations', function ($j){
                        $j->on('public_consultation_translations.public_consultation_id', '=', 'public_consultation.id')
                            ->where('public_consultation_translations.locale', '=', app()->getLocale());
                    })
                    ->leftJoin('files', function ($q){
                        $q->on('files.id_object', '=', 'public_consultation.id')
                            ->where('files.code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
                            ->where('files.doc_type', '=', DocTypesEnum::PC_COMMENTS_REPORT->value)
                            ->whereNull('files.deleted_at');
                    })
                    ->whereNull('public_consultation.deleted_at')
                    ->where('public_consultation.active', '=', 1)
                    ->where('institution.id', '<>', env('DEFAULT_INSTITUTION_ID'))
                    ->groupBy('institution.id')->get();

                    $data = array([
                        'name' => trans_choice('custom.institutions', 1),
                        'pc_cnt' => __('custom.pc_count'),
                        'less_days_cnt' => __('custom.pc_less_then_30_days_count'),
                        'no_less_days_reason_cnt' => __('custom.pc_no_short_reason'),
                        'has_report' => __('custom.pc_missing_docs'),
                        'missing_documents' => __('custom.pc_no_proposal_report'),
                    ]);

                    if($q->count()){
                        foreach ($q as $item){
                            $id = $item->id;
                            $item = (array)$item;
                            unset($item['id']);
                            $item['missing_documents'] = isset($missingFiles) && sizeof($missingFiles) && isset($missingFiles[$id]) && $missingFiles[$id] > 0 ? __('custom.yes') : __('custom.no');
                            $data[] = $item;
                        }
                    }
                break;
            case 'full':
                $defaultInstitution = env('DEFAULT_INSTITUTION_ID');
                $data = DB::select(
                    'select
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
                                            join dynamic_structure_column_translations dsct on dsct.dynamic_structure_column_id = dsc.id and dsct.locale = \'bg\'
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
                                                and f.locale = \'bg\'
                                                and f.code_object = '.File::CODE_OBJ_PUBLIC_CONSULTATION.'
                                        group by f.doc_type
                                        order by f.doc_type
                                    ) A
                                ) as files
                            from public_consultation pc
                            join public_consultation_translations pct on pct.public_consultation_id = pc.id and pct.locale = \'bg\'
                            left join field_of_actions foa on foa.id = pc.field_of_actions_id
                            left join field_of_action_translations foat on foat.field_of_action_id = foa.id and foat.locale = \'bg\'
                            left join act_type at2 on at2.id = pc.act_type_id
                            left join act_type_translations att on att.act_type_id = at2.id and att.locale = \'bg\'
                            left join law l on l.id = pc.law_id
                            left join law_translations lt on lt.law_id = l.id and lt.locale = \'bg\'
                            left join institution i on i.id = pc.responsible_institution_id  and i.id <> '.$defaultInstitution.' --
                            left join institution_translations it on it.institution_id = i.id and it.locale = \'bg\'
                            left join users u on u.id = pc.user_id
                            left join files cf on cf.id_object = pc.id
                                        and cf.code_object = '.File::CODE_OBJ_PUBLIC_CONSULTATION.'
                                        and cf.doc_type = '.DocTypesEnum::PC_KD_PDF->value.'
                                        and cf.locale = \'bg\'
                                        and cf.deleted_at is null
                            where
                                pc.deleted_at is null
                                 and pc.active = 1
                            order by pc.created_at desc'
                );

                $header = [
                    'reg_num' => __('custom.number_symbol'),
                    'consultation_type' => __('custom.consultation_level'),
                    'act_type' => trans_choice('custom.act_type', 1),
                    'date_open' => __('validation.attributes.open_from'),
                    'date_close' => __('validation.attributes.open_to'),
                    'short_term_reason' => __('site.public_consultation.short_term_motive_label'),
                    'active' => __('custom.status'),
                    'policy_area' => trans_choice('custom.field_of_actions', 1),
                    'legislative_program_id' => trans_choice('custom.legislative_program', 1),
                    'operational_program_id' => trans_choice('custom.operational_programs', 1),
                    'responsible_institution_id' => trans_choice('custom.institutions', 1).'(ID)',
                    'responsible_institution_name' => trans_choice('custom.institutions', 1).'('.__('custom.name').')',
                    'responsible_institution_address' => trans_choice('custom.institutions', 1).'('.__('custom.address').')',
                    'proposal_ways' => __('custom.proposal_ways'),
                    'contacts' => trans_choice('custom.person_contacts', 2),
                    'law_name' => trans_choice('custom.nomenclature.laws', 1),
                    'law_id' => trans_choice('custom.nomenclature.laws', 1).'(ID)',
                    'pris_id' => trans_choice('custom.acts_pris', 1).'(ID)',
                    'author_name' => __('custom.author'),
                    'comments' => trans_choice('custom.comment', 2),
                    'consultation_document' => trans_choice('custom.consult_documents', 1)
                ];

                $finalData = array();
                if(sizeof($data)){
                    foreach ($data as $row){
                        if(!empty($row->contacts)){
                            $row->contacts = json_decode($row->contacts, true);
                        }
                        if(!empty($row->comments)){
                            $row->comments = json_decode($row->comments, true);
                        }
                        if(!empty($row->consultation_document)){
                            $row->consultation_document = json_decode($row->consultation_document, true);
                        }
                        if(!empty($row->files)){
                            $row->files = json_decode($row->files, true);
                        }
                        $finalData[] = $row;
                    }
                }
                array_unshift($finalData, $header);
                $data = $finalData;

                break;
            default:
                $data = [];
        }

        return response()->json($data, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function apiReportAdvBoards(Request $request, string $type)
    {
        switch ($type)
        {
            case 'standard':
                $data = DB::select('
                    select
                        max(abt.name) as name,
                        ab.created_at::date as date_established,
                        max(aabt."name") as establishment_act_type,
                        -- max(abort2.description) as establishment_act,
                        (
                            select jsonb_build_object(\'description\', max(abet.description), \'links\', A.files)
                            from (
                                select jsonb_agg(\''.url('/').'\' || \'/download/\' || f.id) as files
                                from files f
                                where
                                    f.deleted_at is null
                                and f.id_object = max(abe.id)
                                and f.doc_type = '.DocTypesEnum::AB_ESTABLISHMENT_RULES->value.'
                                and f.code_object = '.File::CODE_AB.'
                            ) A
                        ) as establishment_act,
                         -- max(abort2.description) as rules_guide,
                        (
                        select jsonb_build_object(\'description\', max(abort2.description), \'links\', A.files)
                            from (
                                select jsonb_agg(\''.url('/').'\' || \'/download/\' || f.id) as files
                                from files f
                                where
                                    f.deleted_at is null
                                and f.id_object = max(abor.id)
                                and f.doc_type = '.DocTypesEnum::AB_ORGANIZATION_RULES->value.'
                                and f.code_object = '.File::CODE_AB.'
                            ) A
                        ) as rules_guide,
                        max(actt.name) as chairman_type,
                        (
                        select jsonb_agg(jsonb_build_object(ct.type_name, ct.members))
                            from (
                                select type_name, jsonb_agg(jsonb_build_object(\'name\', abmt.member_name , \'role\', abmt.member_job)) as members
                                from advisory_board_members abm
                                join advisory_board_member_translations abmt on abmt.advisory_board_member_id = abm.id and abmt.locale = \'bg\'
                                join (select type_id, type_name from (
                                    values ('.AdvisoryTypeEnum::CHAIRMAN->value.', \'chairmen\'),
                                        ('.AdvisoryTypeEnum::VICE_CHAIRMAN->value.', \'vice-chairmen\'),
                                        ('.AdvisoryTypeEnum::SECRETARY->value.', \'secretaries\')
                                    ) E(type_id, type_name)) enums on enums.type_id = abm.advisory_type_id::int
                                where
                                    abm.deleted_at is null
                                and abm.advisory_board_id = ab.id
                                group by type_name
                            ) ct
                        ) as members,
                        ab.meetings_per_year as meetings_year,
                        ab.has_npo_presence as npo_representative,
                        max(abst.description) as secretariate_details,
                        (
                        select jsonb_agg(jsonb_build_object(\'name\', f.custom_name , \'date\', f.created_at::date, \'link\', \''.url('/').'\' || \'/download/\' || f.id)) as files
                                from files f
                                where
                                    f.id_object = max(abs2.id)
                                    and f.deleted_at is null
                                and f.locale = \'bg\'
                                and f.code_object = '.File::CODE_AB.'
                                and f.doc_type = '.DocTypesEnum::AB_SECRETARIAT->value.'
                        ) as secretariate_files,
                        (
                        jsonb_build_object(\'info\', max(abmit.description), \'files\', (
                        select jsonb_agg(jsonb_build_object(\'name\', f.custom_name , \'date\', f.created_at::date, \'link\', \''.url('/').'\' || \'/download/\' || f.id)) as files
                                from files f
                                where
                                    f.id_object = max(abmi.id)
                                    and f.deleted_at is null
                                and f.locale = \'bg\'
                                and f.code_object = '.File::CODE_OBJ_AB_MODERATOR.'
                                and f.doc_type = '.DocTypesEnum::AB_MODERATOR->value.'
                            ), \'persons\',
                                (
                                select jsonb_agg(case when u.first_name is not null then u.first_name else \'\' end || \' \' || case when u.last_name is not null then u.last_name else \'\' end)
                                    from advisory_board_moderators abm2
                                    join users u on u.id = abm2.user_id
                                    where
                                        abm2.deleted_at is null
                                and abm2.advisory_board_id = ab.id
                                )
                            )
                        ) as moderators,
                        (
                        select jsonb_agg(jsonb_build_object(\'year\', WP.working_year::date, \'description\',  WP.description, \'reports\', WP.files))
                            from (
                                select abf.id, max(abf.working_year) as working_year, max(abft.description) as description, jsonb_agg(\''.url('/').'\' || \'/download/\' || f2.id) as files
                                from advisory_board_functions abf
                                join advisory_board_function_translations abft on abft.advisory_board_function_id = abf.id and abft.locale = \'bg\'
                                left join files f2 on f2.id_object = abf.id
                                and f2.deleted_at is null
                                and f2.locale = \'bg\'
                                and f2.code_object = '.File::CODE_AB.'
                                and f2.doc_type = '.DocTypesEnum::AB_FUNCTION->value.'
                                where
                                    abf.deleted_at is null
                                and abf.advisory_board_id = ab.id
                                group by abf.id
                            ) WP
                        ) as work_program,
                        (
                        select jsonb_agg(jsonb_build_object(\'year\', M.next_meeting::date, \'description\',  M.description, \'files\', M.files))
                            from (
                                select abm3.id, max(abm3.next_meeting) as next_meeting, max(abmt2.description) as description, jsonb_agg(\''.url('/').'\' || \'/download/\' || f.id) as files
                                from advisory_board_meetings abm3
                                join advisory_board_meeting_translations abmt2 on abmt2.advisory_board_meeting_id = abm3.id and abmt2.locale = \'bg\'
                                left join files f on f.id_object = abm3.id
                                and f.deleted_at is null
                                and f.locale = \'bg\'
                                and f.code_object = '.File::CODE_AB.'
                                and f.doc_type = '.DocTypesEnum::AB_MEETINGS_AND_DECISIONS->value.'
                                where
                                    abm3.deleted_at is null
                                and abm3.advisory_board_id = ab.id
                                group by abm3.id
                            ) M
                        ) as meetings,
                        (
                        select jsonb_agg(jsonb_build_object(\'date\', p.published_at::date, \'title\',  pt.title , \'content\', pt."content"))
                            from "publication" p
                            join publication_translations pt on pt.publication_id = p.id and pt.locale = \'bg\'
                            where
                                p.deleted_at is null
                                and p.active = true
                                and p.published_at is not null
                                and p.advisory_boards_id = ab.id
                        ) as news
                    from advisory_boards ab
                    join advisory_board_translations abt on abt.advisory_board_id = ab.id and abt.locale = \'bg\'
                    left join authority_advisory_board aab on aab.id = ab.authority_id
                    left join authority_advisory_board_translations aabt on aabt.authority_advisory_board_id = aab.id and aabt.locale = \'bg\'
                    left join advisory_board_establishments abe on abe.advisory_board_id = ab.id
                    left join advisory_board_establishment_translations abet on abet.advisory_board_establishment_id = abe.id and abet.locale = \'bg\'
                    left join advisory_board_organization_rules abor on abor.advisory_board_id = ab.id
                    left join advisory_board_organization_rule_translations abort2 on abort2.advisory_board_organization_rule_id = abor.id and abort2.locale = \'bg\'
                    left join advisory_chairman_type act on act.id = ab.advisory_chairman_type_id
                    left join advisory_chairman_type_translations actt on actt.advisory_chairman_type_id = act.id and actt.locale = \'bg\'
                    left join advisory_board_secretariats abs2 on abs2.advisory_board_id = ab.id and abs2.deleted_at is null
                    left join advisory_board_secretariat_translations abst on abst.advisory_board_secretariat_id = abs2.id and abst.locale = \'bg\'
                    left join advisory_board_moderator_information abmi on abmi.advisory_board_id = ab.id and abmi.deleted_at is null
                    left join advisory_board_moderator_information_translations abmit on abmit.advisory_board_moderator_information_id = abmi.id and abmit.locale = \'bg\'
                    where
                        ab.deleted_at is null
                                and ab.active = true
                                and ab.public = true
                    group by ab.id
                ');
                $header = [
                    'title' => __('custom.title'),
                    'date_established' => __('custom.created_at'),
                    'establishment_act_type' => __('validation.attributes.act_of_creation'),
                    'establishment_act' => __('validation.attributes.act_of_creation').' ('.__('custom.info').')',
                    'rules_guide' => __('custom.rules_internal_organization'),
                    'chairman_type' => __('validation.attributes.advisory_chairman_type_id').' '.trans_choice('custom.chairmen', 1),
                    'members' => trans_choice('custom.member', 2),
                    'meetings_year' => __('validation.attributes.meetings_per_year'),
                    'npo_representative' => __('custom.npo'),
                    'secretariate_details' => __('custom.advisory_board_secretariat').' ('.__('custom.info').')',
                    'secretariate_files' => __('custom.advisory_board_secretariat').' ('.trans_choice('custom.files', 2).')',
                    'moderators' => trans_choice('custom.moderators', 2),
                    'work_program' => trans_choice('custom.function', 2),
                    'meetings' => trans_choice('custom.meetings', 2),
                    'news' => trans_choice('custom.news', 2),
                ];

                $finalData = array();
                if(sizeof($data)){
                    foreach ($data as $row){
                        if(!empty($row->establishment_act)){
                            $row->establishment_act = json_decode($row->establishment_act, true);
                        }
                        if(!empty($row->rules_guide)){
                            $row->rules_guide = json_decode($row->rules_guide, true);
                        }
                        if(!empty($row->members)){
                            $row->members = json_decode($row->members, true);
                        }
                        if(!empty($row->secretariate_files)){
                            $row->secretariate_files = json_decode($row->secretariate_files, true);
                        }
                        if(!empty($row->moderators)){
                            $row->moderators = json_decode($row->moderators, true);
                        }
                        if(!empty($row->work_program)){
                            $row->work_program = json_decode($row->work_program, true);
                        }
                        if(!empty($row->meetings)){
                            $row->meetings = json_decode($row->meetings, true);
                        }
                        if(!empty($row->news)){
                            $row->news = json_decode($row->news, true);
                        }
                        $finalData[] = $row;
                    }
                }
                array_unshift($finalData, $header);
                $data = $finalData;
                break;
            default:
                $data = [];
        }
        return response()->json($data, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }
    public function apiReportPris(Request $request, string $type){
        switch ($type)
        {
            case 'standard':
            case 'archive':
                $inArchive = $type == 'archive' ? 1 : 0;
                DB::enableQueryLog();
                $q = DB::table('pris')
                    ->select([
                        'pris.id as pris_id',
                        'pris.doc_num',
                        'pris.doc_date as doc_accepted_date',
                        DB::raw('max(pris_translations.about) as doc_about'),
                        DB::raw('max(legal_act_type_translations.name) as legal_act_type'),
                        DB::raw('max(pris_translations.legal_reason) as legal_reason'),
                        DB::raw('max(pris_translations.importer) as importer'),
                        DB::raw('json_agg(json_build_object(\'id\', institution.id, \'name\', institution_translations.name)) filter (where institution.id is not null) as institutions'),
                        DB::raw('pris.version'),
                        DB::raw('case
                                            when max(pp.id) is null then pris.protocol
                                            else
                                                case
                                                    when max(pp.protocol_point) is null
                                                    then (max(pp_lat_tr.name_single) || \' \' || \''.__('custom.number_symbol').'\' || max(pp.doc_num) || \' \' || \''.__('custom.of_council').'\' || \' \' || date_part(\'year\',max(pp.doc_date)))
                                                    else (\''.__('site.point_short').'\' || \' \' || \''.__('custom.from').'\' || \' \' || max(pp_lat_tr.name_single) || \' \' || \''.__('custom.number_symbol').'\' || max(pp.doc_num) || \' \' || \''.__('custom.of_council').'\' || \' \' || date_part(\'year\',max(pp.doc_date))) end
                                            end as protocol'),
                         DB::raw('max(public_consultation.reg_num) as public_consultation_number'),
                        'pris.newspaper_number as state_gazette_number',
                        'pris.newspaper_year as state_gazette_year',
                        DB::raw('(pris.active::int)::bool as active'),
                        DB::raw('pris.published_at::date as date_published_at'),
                        DB::raw('pris.deleted_at::date as date_deleted_at'),
                        DB::raw('json_agg(tag_translations.label) filter (where tag_translations.label is not null) as tags'),
                        DB::raw('(
                            select
                                    json_agg(json_build_object(\'relation_type\', case
                                        when pris_change_pris.connect_type = '.PrisDocChangeTypeEnum::CHANGE->value.' then (case when pris_change_pris.pris_id <> pc.id then \''.__('custom.pris.change_enum.CHANGE').'\' else \''.__('custom.pris.change_enum.reverse.CHANGE').'\' end)
                                        else
                                            case when pris_change_pris.connect_type = '.PrisDocChangeTypeEnum::COMPLEMENTS->value.' then (case when pris_change_pris.pris_id <> pc.id then \''.__('custom.pris.change_enum.COMPLEMENTS').'\' else \''.__('custom.pris.change_enum.reverse.COMPLEMENTS').'\' end)
                                            else
                                                case when pris_change_pris.connect_type = '.PrisDocChangeTypeEnum::CANCEL->value.' then (case when pris_change_pris.pris_id <> pc.id then \''.__('custom.pris.change_enum.CANCEL').'\' else \''.__('custom.pris.change_enum.reverse.CANCEL').'\' end)
                                                else
                                                    case when pris_change_pris.connect_type = '.PrisDocChangeTypeEnum::SEE_IN->value.' then (case when pris_change_pris.pris_id <> pc.id then \''.__('custom.pris.change_enum.SEE_IN').'\' else \''.__('custom.pris.change_enum.reverse.SEE_IN').'\' end)
                                                    else \'\' end
                                                end
                                            end
                                        end, \'pris_id\', pc.id, \'act_type\', pc_act_tr.name_single, \'act_name\', (pc_act_tr.name_single || \' \' || \''.__('custom.number_symbol').'\' || pc.doc_num || \' \' || \''.__('custom.of_council').'\' || \' \' || date_part(\'year\',pc.doc_date)) ))
                                 from pris_change_pris
                                 join pris as pc on pc.id = (case when pris_change_pris.pris_id = pris.id then pris_change_pris.changed_pris_id else pris_change_pris.pris_id end)
                                 join pris_translations as pc_tr on pc_tr.pris_id = pc.id and pc_tr.locale = \'bg\'
                                 join legal_act_type as pc_act on pc_act.id = pc.legal_act_type_id
                                 join legal_act_type_translations as pc_act_tr on pc_act_tr.legal_act_type_id = pc_act.id and pc_act_tr.locale = \'bg\'
                                 where
                                    (pris_change_pris.pris_id = pris.id
                                    or pris_change_pris.changed_pris_id = pris.id)
                                    and pc.deleted_at is null
                             ) as related'),
                    ])
                    ->join('pris_translations', function ($q){
                        $q->on('pris_translations.pris_id', '=', 'pris.id')->where('pris_translations.locale', '=', 'bg');
                    })
                    ->join('legal_act_type', 'legal_act_type.id', '=', 'pris.legal_act_type_id')
                    ->join('legal_act_type_translations', function ($j){
                        $j->on('legal_act_type_translations.legal_act_type_id', '=', 'legal_act_type.id')
                            ->where('legal_act_type_translations.locale', '=', app()->getLocale());
                    })
                    //Public consultation
                    ->leftJoin('public_consultation', 'public_consultation.id', '=', 'pris.public_consultation_id')
                    //Institutions
                    ->leftJoin('pris_institution', 'pris_institution.pris_id', '=', 'pris.id')
                    ->leftJoin('institution', 'institution.id', '=', 'pris_institution.institution_id')
                    ->leftJoin('institution_translations', function ($j){
                        $j->on('institution_translations.institution_id', '=', 'institution.id')
                            ->where('institution_translations.locale', '=', app()->getLocale());
                    })
                    //Protocol
                    ->leftJoin('pris as pp', 'pp.id', '=', 'pris.decision_protocol')
                    ->leftJoin('pris_translations as pp_tr', function ($q){
                        $q->on('pp_tr.pris_id', '=', 'pp.id')->where('pp_tr.locale', '=', 'bg');
                    })
                    ->leftJoin('legal_act_type as pp_lat', 'pp_lat.id', '=', 'pp.legal_act_type_id')
                    ->leftJoin('legal_act_type_translations as pp_lat_tr', function ($j){
                        $j->on('pp_lat_tr.legal_act_type_id', '=', 'pp_lat.id')
                            ->where('pp_lat_tr.locale', '=', app()->getLocale());
                    })
                    //Tags
                    ->leftJoin('pris_tag', 'pris_tag.pris_id', '=', 'pris.id')
                    ->leftJoin('tag', 'tag.id', '=', 'pris_tag.tag_id')
                    ->leftJoin('tag_translations', function ($j){
                        $j->on('tag_translations.tag_id', '=', 'tag.id')
                            ->where('tag_translations.locale', '=', app()->getLocale());
                    })
                    ->whereNull('pris.deleted_at')
//                    ->where('pris.legal_act_type_id', '<>', LegalActType::TYPE_ARCHIVE)
                    ->whereNotNull('pris.published_at')
                    ->whereIn('pris.legal_act_type_id', [LegalActType::TYPE_DECREES, LegalActType::TYPE_DECISION, LegalActType::TYPE_PROTOCOL_DECISION, LegalActType::TYPE_DISPOSITION, LegalActType::TYPE_PROTOCOL, LegalActType::TYPE_ARCHIVE])
                    ->where('pris.asap_last_version', '=', 1)
                    ->where('pris.in_archive', '=', $inArchive)
//                    ->whereNotNull('pris.public_consultation_id')
//                    ->where('pris.id', '=', 161921)
                    ->groupBy('pris.id')
                    ->orderBy('pris.doc_date', 'desc')
                    ->limit(1000);

                $data = $q->get()->map(function ($row) {
                    if(!empty($row->institutions)){
                        $row->institutions = json_decode($row->institutions, true);
                    }
                    if(!empty($row->related)){
                        $row->related = json_decode($row->related, true);
                    }
                    return (array)$row;
                })->toArray();

                $header = [
                    'pris_id' => 'ID',
                    'doc_num' => __('custom.document_number'),
                    'doc_accepted_date' => __('custom.date_issued'),
                    'doc_about' => __('custom.about'),
                    'legal_act_type' => trans_choice('custom.act_types', 1),
                    'legal_reason' => __('custom.legal_reason'),
                    'importer' => trans_choice('custom.importers', 1),
                    'institutions' => trans_choice('custom.institutions', 2),
                    'version' => __('custom.version'),
                    'protocol' => __('site.protocol'),
                    'public_consultation_number' => trans_choice('custom.public_consultations', 1),
                    'state_gazette_number' => __('custom.newspaper_number'),
                    'state_gazette_year' => __('custom.newspaper_year'),
                    'active' => __('custom.status'),
                    'date_published_at' => __('custom.date_published'),
                    'date_updated_at' => __('custom.updated_at'),
                    'date_deleted_at' => __('custom.deleted_at'),
                    'tags' => trans_choice('custom.tags', 2),
                    'related' => __('custom.change_docs'),
                ];
                array_unshift($data, $header);

                break;
            default:
                $data = [];
        }

        return response()->json($data, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function apiReportImpactAssessments(Request $request, string $type){
        switch ($type)
        {
//            case 'standard':
//                $q = DB::table('form_input')
//                    ->select([
//                        DB::raw('to_char(form_input.created_at, \'DD.MM.YYYY\') as date'),
//                        DB::raw('case when form_input.form = \'form1\'
//                            then \'Частична предварителна оценка на въздействието\'
//                            else case when form_input.form = \'form2\'
//                                then \'Резюме на цялостна предварителна оценка на въздействието\'
//                                else case when form_input.form = \'form3\'
//                                    then \'Доклад на цялостна предварителна оценка на въздействието\'
//                                    else \'Цялостна предварителна-доклад\' end
//                                end
//                            end as type'),
//                        DB::raw('trim(\'"\' FROM (data::json->\'institution\')::text) as institution_name'),
//                        DB::raw('case when users.id is not null then users.first_name || \' \' || users.middle_name || \' \' || users.last_name else \'\' end as user_name'),
//                        'form_input.data',
//                    ])
//                    ->leftJoin('users', 'users.id' , '=', 'form_input.user_id')
//                    ->whereNull('form_input.deleted_at')
//                    ->orderBy('form_input.created_at', 'desc');
//
//                $data = $q->get()->map(fn ($row) => (array)$row)->toArray();
//
//                $header = [
//                    'date' => __('custom.date'),
//                    'type' => __('custom.type'),
//                    'institution_name' => trans_choice('custom.institution', 1),
//                    'user_name' => __('custom.author'),
//                    'data' => __('custom.information')
//                ];
//                array_unshift($data, $header);
//
//                break;
            case 'executors':
                $q = DB::table('executors')
                    ->select([
                        'executors.eik',
                        'executors.contract_date as date_contract',
                        'executors.price',
                        'executors.active',
                        'institution_translations.name as institution_id',
                        'institution_translations.name as institution_name',
                        'executor_translations.executor_name as executor',
                        'executor_translations.contract_subject',
                        'executor_translations.services_description',
                    ])
                    ->leftJoin('executor_translations', function ($j){
                        $j->on('executor_translations.executor_id', '=', 'executors.id')
                            ->where('executor_translations.locale', '=', 'bg');
                    })
                    ->join('institution', 'institution.id', '=', 'executors.institution_id')
                    ->join('institution_translations', function ($j){
                        $j->on('institution_translations.institution_id', '=', 'institution.id')
                            ->where('institution_translations.locale', '=', app()->getLocale());
                    })
                    ->whereNull('executors.deleted_at')
                    ->where('executors.active', true)
                    ->orderBy('executor_translations.executor_name', 'asc');

                $data = $q->get()->map(fn ($row) => (array)$row)->toArray();

                $header = [
                    'executor_name' => __('site.executor_name'),
                    'eik' => __('custom.eik'),
                    'contract_date' => __('custom.contract_date'),
                    'price' => __('custom.price_with_vat'),
                    'institution' => __('site.executor_institution'),
                    'contract_subject' => __('custom.contract_subject'),
                    'services_description' => __('custom.services_description'),

                ];
                array_unshift($data, $header);

                break;
            default:
                $data = [];
        }

        return response()->json($data, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function apiReportLibrary(Request $request, string $type = 'standard'){
        switch ($type)
        {
            case 'standard':
                $q = DB::table('publication')
                    ->select([
                        'publication_translations.title',
                        DB::raw('to_char(publication.published_at, \'DD.MM.YYYY\') as date'),
//                        DB::raw('case when users.id is not null then users.first_name || \' \' || users.middle_name || \' \' || users.last_name else \'\' end as author'),
                        'publication_translations.content'
                    ])
                    ->leftJoin('users', 'users.id' , '=', 'publication.users_id')
                    ->leftJoin('publication_translations', function ($j){
                        $j->on('publication_translations.publication_id', '=', 'publication.id')
                            ->where('publication_translations.locale', '=', 'bg');
                    })
                    ->whereNull('publication.deleted_at')
                    ->whereIn('publication.type', [PublicationTypesEnum::TYPE_LIBRARY, PublicationTypesEnum::TYPE_NEWS])
                    ->whereNotNull('publication.published_at')
                    ->where('publication.active', true)
                    ->orderBy('publication.published_at', 'desc');

                $data = $q->get()->map(fn ($row) => (array)$row)->toArray();

                $header = [
                    'title' => __('custom.title'),
                    'date' => __('custom.date'),
//                    'author' => __('custom.author'),
                    'content' => __('custom.content')

                ];
                array_unshift($data, $header);

                break;
            default:
                $data = [];
        }

        return response()->json($data, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function apiReportPolls(Request $request, string $type = 'standard'){
        switch ($type)
        {
            case 'standard':
                $data = DB::select(
                    'select
                            "poll"."name",
                            "public_consultation"."reg_num" as "consultation_reg_num",
                            "poll"."start_date" as "date_start",
                            "poll"."end_date" as "date_end",
                            "poll"."only_registered",
                            (
                            select
                                    json_agg(json_build_object(\'name\', poll_question.name, \'options\',
                                        (
                                        select json_agg(json_build_object(\'name\', poll_question_option.name, \'votes\', (select count(upo.user_poll_id) from user_poll_option upo where upo.poll_question_option_id = poll_question_option.id)))
                                        from poll_question_option
                                        where poll_question_option.poll_question_id = poll_question.id and poll_question_option.deleted_at is null
                                    )))
                                 from poll_question
                                 where poll_question.poll_id = poll.id and poll_question.deleted_at is null
                             ) as questions
                        from "poll"
                        left join "public_consultation" on "public_consultation"."id" = "poll"."consultation_id"
                        where "poll"."deleted_at" is null
                        order by "poll"."start_date" desc'
                );

                $header = [
                    'name' => __('custom.name'),
                    'consultation_reg_num' => trans_choice('custom.publications',1),
                    'date_start' => __('custom.begin_date'),
                    'date_end' => __('custom.end_date'),
                    'only_registered' => __('validation.attributes.only_registered'),
                    'questions' => trans_choice('custom.questions', 2)
                ];

                $finalData = array();
                if(sizeof($data)){
                    foreach ($data as $row){
                        if(!empty($row->questions)){
                            $row->questions = json_decode($row->questions, true);
                        }
                        $finalData[] = $row;
                    }
                }
                array_unshift($finalData, $header);
                $data = $finalData;
//                array_unshift($data, $header);

                break;
            default:
                $data = [];
        }

        return response()->json($data, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }
}
