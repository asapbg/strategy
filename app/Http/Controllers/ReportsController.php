<?php

namespace App\Http\Controllers;

use App\Enums\DocTypesEnum;
use App\Models\ActType;
use App\Models\AdvisoryBoard;
use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
use App\Models\File;
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
                            ->where('advisory_board_translations.locale', '=', app()->getLocale());
                    })
                    ->leftJoin('field_of_actions', 'field_of_actions.id', '=', 'advisory_boards.policy_area_id')
                    ->leftJoin('field_of_action_translations', function ($j){
                        $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                            ->where('field_of_action_translations.locale', '=', app()->getLocale());
                    })
                    ->leftJoin('authority_advisory_board', 'authority_advisory_board.id', '=', 'advisory_boards.authority_id')
                    ->leftJoin('authority_advisory_board_translations', function ($j){
                        $j->on('authority_advisory_board_translations.authority_advisory_board_id', '=', 'authority_advisory_board.id')
                            ->where('authority_advisory_board_translations.locale', '=', app()->getLocale());
                    })
                    ->leftJoin('advisory_act_type', 'advisory_act_type.id', '=', 'advisory_boards.advisory_act_type_id')
                    ->leftJoin('advisory_act_type_translations', function ($j){
                        $j->on('advisory_act_type_translations.advisory_act_type_id', '=', 'advisory_act_type.id')
                            ->where('advisory_act_type_translations.locale', '=', app()->getLocale());
                    })
                    ->leftJoin('advisory_chairman_type', 'advisory_chairman_type.id', '=', 'advisory_boards.advisory_chairman_type_id')
                    ->leftJoin('advisory_chairman_type_translations', function ($j){
                        $j->on('advisory_chairman_type_translations.advisory_chairman_type_id', '=', 'advisory_chairman_type.id')
                            ->where('advisory_chairman_type_translations.locale', '=', app()->getLocale());
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
}
