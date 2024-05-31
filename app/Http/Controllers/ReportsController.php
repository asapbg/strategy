<?php

namespace App\Http\Controllers;

use App\Enums\DocTypesEnum;
use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Enums\PublicationTypesEnum;
use App\Models\ActType;
use App\Models\AdvisoryBoard;
use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
use App\Models\File;
use App\Models\LegalActType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function apiReportSd(Request $request, string $type){
        switch ($type)
        {
            case 'standard':
                $q = DB::table('advisory_boards')
                    ->select([
                        'advisory_board_translations.name',
                        'field_of_action_translations.name as field_of_action',
                        'authority_advisory_board_translations.name as authority',
                        'advisory_act_type_translations.name as advisory_act_type',
                        'advisory_chairman_type_translations.name as chairman_type',
                        DB::raw('case when advisory_boards.has_npo_presence = true then \'Да\' else \'Не\' end as has_npo_presence'),
                        DB::raw('concat(advisory_boards.meetings_per_year, 0) as meetings_per_year'),
                        DB::raw('case when advisory_boards.active = true then \'Активен\' else \'Неактивен\' end as status'),
                    ])
                    ->leftJoin('advisory_board_translations', function ($j){
                        $j->on('advisory_board_translations.advisory_board_id', '=', 'advisory_boards.id')
                            ->where('advisory_board_translations.locale', '=', 'bg');
                    })
                    ->leftJoin('field_of_actions', 'field_of_actions.id', '=', 'advisory_boards.policy_area_id')
                    ->leftJoin('field_of_action_translations', function ($j){
                        $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                            ->where('field_of_action_translations.locale', '=', 'bg');
                    })
                    ->leftJoin('authority_advisory_board', 'authority_advisory_board.id', '=', 'advisory_boards.authority_id')
                    ->leftJoin('authority_advisory_board_translations', function ($j){
                        $j->on('authority_advisory_board_translations.authority_advisory_board_id', '=', 'authority_advisory_board.id')
                            ->where('authority_advisory_board_translations.locale', '=', 'bg');
                    })
                    ->leftJoin('advisory_act_type', 'advisory_act_type.id', '=', 'advisory_boards.advisory_act_type_id')
                    ->leftJoin('advisory_act_type_translations', function ($j){
                        $j->on('advisory_act_type_translations.advisory_act_type_id', '=', 'advisory_act_type.id')
                            ->where('advisory_act_type_translations.locale', '=', 'bg');
                    })
                    ->leftJoin('advisory_chairman_type', 'advisory_chairman_type.id', '=', 'advisory_boards.advisory_chairman_type_id')
                    ->leftJoin('advisory_chairman_type_translations', function ($j){
                        $j->on('advisory_chairman_type_translations.advisory_chairman_type_id', '=', 'advisory_chairman_type.id')
                            ->where('advisory_chairman_type_translations.locale', '=', 'bg');
                    })
                    ->whereNull('advisory_boards.deleted_at')
                    ->where('advisory_boards.public', true)
                    ->orderBy('advisory_boards.active', 'desc')
                    ->orderBy('advisory_board_translations.name', 'asc');

                $data = $q->get()->map(fn ($row) => (array)$row)->toArray();
                $header = [
                    'name' => __('custom.name'),
                    'field_of_action' => trans_choice('custom.field_of_actions', 1),
                    'authority' => __('custom.type_of_governing'),
                    'advisory_act_type' => __('validation.attributes.act_of_creation'),
                    'chairman_type' => __('validation.attributes.advisory_chairman_type_id'),
                    'has_npo_presence' => 'Представител на НПО',
                    'meetings_per_year' => 'Мин. бр. заседания на година',
                    'status' => __('custom.status'),
                ];
                array_unshift($data, $header);

                break;
            default:
                $data = [];
        }

        return response()->json($data);
    }

    public function apiReportLegislativeInitiative(Request $request, string $type){
        switch ($type)
        {
            case 'standard':
                $q = DB::table('legislative_initiative')
                    ->select([
                        DB::raw('\''.__('custom.change_f').' '.__('custom.in').'\' || max(law_translations.name) as name'),
                        DB::raw('legislative_initiative.description as description'),
                        DB::raw('legislative_initiative.law_paragraph as law_paragraph'),
                        DB::raw('legislative_initiative.created_at::date as start_at'),
                        DB::raw('legislative_initiative.active_support::date as support_end_at'),
                        'legislative_initiative.cap as required_likes',
                        DB::raw('case when max(users.id) is not null then max(users.first_name) || \' \' || max(users.last_name) else \'\' end as author'),
                        DB::raw('case when legislative_initiative.status::int = '.LegislativeInitiativeStatusesEnum::STATUS_SEND->value.'
                        then \''.__('custom.legislative_' . \Illuminate\Support\Str::lower(LegislativeInitiativeStatusesEnum::STATUS_SEND->name)).'\'
                        else (
                            case when legislative_initiative.status::int = '.LegislativeInitiativeStatusesEnum::STATUS_CLOSED->value.'
                            then \''.__('custom.legislative_' . \Illuminate\Support\Str::lower(LegislativeInitiativeStatusesEnum::STATUS_CLOSED->name)).'\'
                            else \''.__('custom.legislative_' . \Illuminate\Support\Str::lower(LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->name)).'\' end
                        ) end as status'),
                        DB::raw('sum(case when legislative_initiative_votes.is_like = true then 1 else 0 end) as likes'),
                        DB::raw('sum(case when legislative_initiative_votes.is_like = false then 1 else 0 end) as dislikes'),
                        DB::raw('0 as supported'),
                        DB::raw('legislative_initiative.end_support_at::date as end_at'),
                        DB::raw('json_agg(ic_translation.name) filter (where ic_translation.name is not null) as initiative_institution'),
                        DB::raw('json_agg(ric_translation.name) filter (where ric_translation.name is not null) as send_to_institution'),
                        DB::raw('legislative_initiative.send_at::date as send_at')
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
                    ->orderBy('legislative_initiative.created_at', 'desc')
                    ->get();

                $data = array();
                if($q->count()){
                    foreach ($q as $row){
                        $rowArray = (array)$row;
                        $liInstitutions = !is_null($rowArray['initiative_institution']) ? json_decode($rowArray['initiative_institution']) : [];
                        $rowArray['initiative_institution'] = implode(', ', $liInstitutions);
                        $liSendInstitutions = !is_null($rowArray['send_to_institution']) ? json_decode($rowArray['send_to_institution']) : [];
                        $rowArray['send_to_institution'] = implode(', ', $liSendInstitutions);

                        $supported = (int)$rowArray['likes'] - (int)$rowArray['dislikes'];
                        $rowArray['supported'] = $supported >= 0 ? $supported : 0;

                        $rowArray['description'] = !empty($rowArray['description']) ? strip_tags($rowArray['description']) : '';
                        $rowArray['law_paragraph'] = !empty($rowArray['law_paragraph']) ? strip_tags($rowArray['law_paragraph']) : '';
                        $rowArray['law_text'] = !empty($rowArray['law_text']) ? strip_tags($rowArray['law_text']) : '';

                        $data[] = $rowArray;
                    }
                }
                //$data = $q->get()->map(fn ($row) => (array)$row)->toArray();

                $header = [
                    'name' => __('custom.name'),
                    'description' => __('custom.description_of_suggested_change'),
                    'law_paragraph' => __('validation.attributes.law_paragraph'),
                    'law_text' => __('validation.attributes.law_text'),
                    'start_at' => __('custom.begin_date'),
                    'support_end_at' => __('site.li_end_support_date'),
                    'required_likes' => __('site.li_required_likes'),
                    'author' => __('custom.author'),
                    'status' => __('custom.status'),
                    'likes' => __('custom.likes'),
                    'dislikes' => __('custom.li_dislikes'),
                    'supported' => __('custom.supported_f'),
                    'end_at' => __('site.li_ended_at'),
                    'initiative_institution' => __('site.li_initiative_institution'),
                    'send_to_institution' => __('site.li_send_to_administrations'),
                    'send_at' => __('site.li_send_at'),

                ];
                array_unshift($data, $header);

                break;
            default:
                $data = [];
        }

        return response()->json($data);
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
            default:
                $data = [];
        }

        return response()->json($data);
    }

    public function apiReportPris(Request $request, string $type){
        switch ($type)
        {
            case 'standard':
                DB::enableQueryLog();
                $q = DB::table('pris')
                    ->select([
                        'pris.doc_num',
                        'pris.doc_date',
                         DB::raw('pris.published_at::date as published_at'),
                         DB::raw('max(legal_act_type_translations.name) as legal_act_type'),
                         DB::raw('json_agg(institution_translations.name) filter (where institution_translations.name is not null and institution_translations.institution_id <> '.env('DEFAULT_INSTITUTION_ID', 0).') as institutions'),
                         DB::raw('max(pris_translations.importer) as importer'),
                         'pris.protocol',
                         'pris.newspaper_number',
                         'pris.newspaper_year',
                        DB::raw('json_agg(tag_translations.label) filter (where tag_translations.label is not null) as tags'),
                         DB::raw('max(pris_translations.about) as about'),
                         DB::raw('max(pris_translations.legal_reason) as legal_reason')
                    ])
                    ->join('pris_translations', function ($q){
                        $q->on('pris_translations.pris_id', '=', 'pris.id')->where('pris_translations.locale', '=', 'bg');
                    })
                    ->join('legal_act_type', 'legal_act_type.id', '=', 'pris.legal_act_type_id')
                    ->join('legal_act_type_translations', function ($j){
                        $j->on('legal_act_type_translations.legal_act_type_id', '=', 'legal_act_type.id')
                            ->where('legal_act_type_translations.locale', '=', app()->getLocale());
                    })
                    ->join('pris_institution', 'pris_institution.pris_id', '=', 'pris.id')
                    ->join('institution', 'institution.id', '=', 'pris_institution.institution_id')
                    ->join('institution_translations', function ($j){
                        $j->on('institution_translations.institution_id', '=', 'institution.id')
                            ->where('institution_translations.locale', '=', app()->getLocale());
                    })
                    ->join('pris_tag', 'pris_tag.pris_id', '=', 'pris.id')
                    ->join('tag', 'tag.id', '=', 'pris_tag.tag_id')
                    ->join('tag_translations', function ($j){
                        $j->on('tag_translations.tag_id', '=', 'tag.id')
                            ->where('tag_translations.locale', '=', app()->getLocale());
                    })
                    ->whereNull('pris.deleted_at')
                    ->where('pris.legal_act_type_id', '<>', LegalActType::TYPE_ARCHIVE)
                    ->whereNotNull('pris.published_at')
                    ->whereIn('pris.legal_act_type_id', [LegalActType::TYPE_DECREES, LegalActType::TYPE_DECISION, LegalActType::TYPE_PROTOCOL_DECISION, LegalActType::TYPE_DISPOSITION, LegalActType::TYPE_PROTOCOL])
                    ->where('pris.asap_last_version', '=', 1)
                    ->where('pris.in_archive', 0)
                    ->groupBy('pris.id')
                    ->orderBy('pris.doc_date', 'desc')
                    ->limit(1000)
                ->get();

                $data = array();

                if($q->count()){
                    foreach ($q as $row){
                        $rowArray = (array)$row;
                        $institutions = !is_null($rowArray['institutions']) ? json_decode($rowArray['institutions']) : [];
                        $rowArray['institutions'] = implode(', ', $institutions);

                        $tags = !is_null($rowArray['tags']) ? json_decode($rowArray['tags']) : [];
                        $rowArray['tags'] = implode(', ', $tags);

                        $rowArray['about'] = !empty($rowArray['about']) ? html_entity_decode($rowArray['about']) : '';
                        $rowArray['legal_reason'] = !empty($rowArray['legal_reason']) ? html_entity_decode($rowArray['legal_reason']) : '';
                        $data[] = $rowArray;
                    }
                }
                $header = [
                    'name' => __('custom.document_number'),
                    'doc_date' => __('custom.date_issued'),
                    'published_at' => __('custom.date_published'),
                    'legal_act_type' => trans_choice('custom.act_types', 1),
                    'institutions' => trans_choice('custom.institutions', 2),
                    'importer' => trans_choice('custom.importers', 1),
                    'protocol' => __('site.protocol'),
                    'newspaper_number' => __('custom.newspaper_number'),
                    'newspaper_year' => __('custom.newspaper_year'),
                    'tags' => trans_choice('custom.tags', 2),
                    'about' => __('custom.about'),
                    'legal_reason' => __('custom.legal_reason'),
                ];
                array_unshift($data, $header);

                break;
            default:
                $data = [];
        }

        return response()->json($data);
    }

    public function apiReportImpactAssessments(Request $request, string $type){
        switch ($type)
        {
            case 'standard':
                $q = DB::table('form_input')
                    ->select([
                        DB::raw('to_char(form_input.created_at, \'DD.MM.YYYY\') as date'),
                        DB::raw('case when form_input.form = \'form1\'
                            then \'Частична предварителна оценка на въздействието\'
                            else case when form_input.form = \'form2\'
                                then \'Резюме на цялостна предварителна оценка на въздействието\'
                                else case when form_input.form = \'form3\'
                                    then \'Доклад на цялостна предварителна оценка на въздействието\'
                                    else \'Цялостна предварителна-доклад\' end
                                end
                            end as type'),
                        DB::raw('trim(\'"\' FROM (data::json->\'institution\')::text) as institution_name'),
                        DB::raw('case when users.id is not null then users.first_name || \' \' || users.middle_name || \' \' || users.last_name else \'\' end as user_name'),
                        'form_input.data',
                    ])
                    ->leftJoin('users', 'users.id' , '=', 'form_input.user_id')
                    ->whereNull('form_input.deleted_at')
                    ->orderBy('form_input.created_at', 'desc');

                $data = $q->get()->map(fn ($row) => (array)$row)->toArray();

                $header = [
                    'date' => __('custom.date'),
                    'type' => __('custom.type'),
                    'institution_name' => trans_choice('custom.institution', 1),
                    'user_name' => __('custom.author'),
                    'data' => __('custom.information')
                ];
                array_unshift($data, $header);

                break;
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

        return response()->json($data);
    }

    public function apiReportLibrary(Request $request, string $type = 'standard'){
        switch ($type)
        {
            case 'standard':
                $q = DB::table('publication')
                    ->select([
                        'publication_translations.title',
                        DB::raw('to_char(publication.published_at, \'DD.MM.YYYY\') as date'),
                        DB::raw('case when users.id is not null then users.first_name || \' \' || users.middle_name || \' \' || users.last_name else \'\' end as author'),
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
                    'author' => __('custom.author'),
                    'content' => __('custom.content')

                ];
                array_unshift($data, $header);

                break;
            default:
                $data = [];
        }

        return response()->json($data);
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
                array_unshift($data, $header);

                break;
            default:
                $data = [];
        }

        return response()->json($data);
    }
}
