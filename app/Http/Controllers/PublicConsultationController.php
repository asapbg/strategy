<?php

namespace App\Http\Controllers;

use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Exports\PublicConsultationFaReportExport;
use App\Exports\PublicConsultationInstitutionReportExport;
use App\Exports\PublicConsultationReportExport;
use App\Http\Requests\StoreCommentRequest;
use App\Models\ActType;
use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
use App\Models\FieldOfAction;
use App\Models\File;
use App\Models\Setting;
use App\Models\StrategicDocuments\Institution;
use App\Models\UserSubscribe;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class PublicConsultationController extends Controller
{
    public function index(Request $request)
    {
        $rssUrl = config('feed.feeds.public_consultation.url');
        $rf = $request->all();
        $requestFilter = $request->all();

        //Filter
        $filter = $this->filters($request);
        //Sorter
        $sorter = $this->sorters();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'date';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');
        $paginate = $requestFilter['paginate'] ?? config('app.default_paginate');

        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        $pk = PublicConsultation::select('public_consultation.*')
            ->ActivePublic()
            ->with(['translation'])
            ->join('public_consultation_translations', function ($j){
                $j->on('public_consultation_translations.public_consultation_id', '=', 'public_consultation.id')
                    ->where('public_consultation_translations.locale', '=', app()->getLocale());
            })
            ->leftjoin('act_type', 'act_type.id', '=', 'public_consultation.act_type_id')
            ->leftjoin('act_type_translations', function ($j){
                $j->on('act_type_translations.act_type_id', '=', 'act_type.id')
                    ->where('act_type_translations.locale', '=', app()->getLocale());
            })
            ->join('field_of_actions', 'field_of_actions.id', '=', 'public_consultation.field_of_actions_id')
            ->join('field_of_action_translations', function ($j){
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->FilterBy($requestFilter)
            ->SortedBy($sort,$sortOrd)
            ->paginate($paginate);

        $hasSubscribeEmail = $this->hasSubscription(null, PublicConsultation::class, $requestFilter);
        $hasSubscribeRss = false;

        $closeSearchForm = true;
        if( $request->ajax() ) {
            $closeSearchForm = false;
            return view('site.public_consultations.list', compact('filter','sorter', 'pk', 'rf', 'hasSubscribeEmail', 'hasSubscribeRss', 'requestFilter', 'rssUrl', 'closeSearchForm'));
        }

        $pageTitle = __('site.menu.public_consultation');
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_PC.'_'.app()->getLocale())->first();
        return $this->view('site.public_consultations.index', compact('filter', 'sorter', 'pk', 'pageTitle', 'pageTopContent', 'defaultOrderBy', 'defaultDirection', 'hasSubscribeEmail', 'requestFilter', 'rssUrl', 'closeSearchForm'));
    }

    public function show(Request $request, int $id = 0)
    {
//        return $this->view('templates.public_consultations_view');
        $item = PublicConsultation::ActivePublic()->with(['translation', 'actType', 'actType.translation', 'contactPersons',
            'pollsInPeriod', 'pollsInPeriod.questions', 'pollsInPeriod.questions.answers', 'importerInstitution', 'importerInstitution.links',
            'importerInstitution.links.translations', 'fieldOfAction', 'fieldOfAction.translation'])->find($id);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $pageTitle = $item->title;

        $breadcrumbs = array(
            ['name' => trans_choice('custom.public_consultations', 2), 'url' => route('public_consultation.index')]
        );
        if($item->fieldOfAction){
            $breadcrumbs[] = ['name' => $item->fieldOfAction->name, 'url' => route('public_consultation.index').'?fieldOfActions[]='.$item->fieldOfAction->id];
        }
        $breadcrumbs[] = ['name' => $item->title, 'url' => ''];
        $this->setBreadcrumbsFull($breadcrumbs);

        $documents = $item->lastDocumentsByLocaleAndSection(true);
        $documentsImport = $item->lastDocumentsByLocaleImport();
        $timeline = $item->orderTimeline();
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_PC.'_'.app()->getLocale())->first();
        $this->setSeo($item->title,  $item->description, '', array('title' => $item->title, 'img' => PublicConsultation::DEFAULT_IMG));

        $hasSubscribeEmail = $this->hasSubscription($item);
        $hasSubscribeRss = false;
        return $this->view('site.public_consultations.view', compact('item', 'pageTitle', 'documents', 'timeline', 'pageTopContent', 'documentsImport', 'hasSubscribeEmail', 'hasSubscribeRss'));
    }

    public function addComment(StoreCommentRequest $request)
    {
        $validated = $request->validated();
        $pc = PublicConsultation::find($validated['id']);
        if( !$pc->inPeriodBoolean ){
            return back()->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            $pc->comments()->save(new Comments([
                'object_code' => Comments::PC_OBJ_CODE,
                'content' => $validated['content'],
                'user_id' => $request->user() ? $request->user()->id : null,
            ]));
            DB::commit();
            return redirect(route('public_consultation.view', ['id' => $pc->id]) )
                ->with('success', __('site.successful_send_comment'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Save comment error: '.$e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function simpleReport(Request $request)
    {
        $rf = $request->all();
        $requestFilter = $request->all();
        //Filter
        $filter = $this->filtersReport($request, $rf);

        if(isset($requestFilter['level'])){
            $requestFilter['levels'] = $requestFilter['level'];
            unset($requestFilter['level']);
        }

        //Sorter
        //$sorter = $this->sortersReport();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'date';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');
        $paginate = $requestFilter['paginate'] ?? config('app.default_paginate');

        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        $q = PublicConsultation::select(['public_consultation.*', DB::raw('json_agg(distinct(files.doc_type)) filter (where files.doc_type is not null) as doc_types')])
            ->Active()
            ->with(['translation', 'comments', 'fieldOfAction', 'fieldOfAction.translations',
                'actType', 'actType.translations',
                'importerInstitution', 'importerInstitution.translations', 'comments', 'proposalReport'])
            ->leftJoin('institution', 'institution.id', '=', 'public_consultation.importer_institution_id')
            ->join('public_consultation_translations', function ($j){
                $j->on('public_consultation_translations.public_consultation_id', '=', 'public_consultation.id')
                    ->where('public_consultation_translations.locale', '=', app()->getLocale());
            })
            ->leftjoin('act_type', 'act_type.id', '=', 'public_consultation.act_type_id')
            ->leftjoin('act_type_translations', function ($j){
                $j->on('act_type_translations.act_type_id', '=', 'act_type.id')
                    ->where('act_type_translations.locale', '=', app()->getLocale());
            })
            ->join('field_of_actions', 'field_of_actions.id', '=', 'public_consultation.field_of_actions_id')
            ->join('field_of_action_translations', function ($j){
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->leftjoin('comments', function ($j){
                $j->on('comments.object_id', '=', 'public_consultation.id')
                    ->where('comments.object_code', '=', Comments::PC_OBJ_CODE);
            })
            ->leftjoin('files', function ($j){
                $j->on('files.id_object', '=', 'public_consultation.id')
                    ->where('files.code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
                    ->where('files.locale', '=', app()->getLocale())
                    ->whereIn('files.doc_type', DocTypesEnum::pcDocTypes());
            })
//            ->where('institution.id', '<>', env('DEFAULT_INSTITUTION_ID'))
            ->FilterBy($requestFilter)
            ->SortedBy($sort,$sortOrd)
            ->groupBy('public_consultation.id');

        if($request->input('export_excel') || $request->input('export_pdf')){
            $items = $q->get();
            $exportData = [
                'title' => __('custom.pc_report_title'),
                'rows' => $items
            ];

            $fileName = 'pc_report_'.Carbon::now()->format('Y_m_d_H_i_s');
            if($request->input('export_pdf')){
                $pdf = PDF::loadView('exports.pc_report', ['data' => $exportData, 'isPdf' => true])->setPaper('a4', 'landscape');
                return $pdf->download($fileName.'.pdf');
            } else{
                return Excel::download(new PublicConsultationReportExport($exportData), $fileName.'.xlsx');
            }
        } else{
            $items = $q->paginate($paginate);
        }

        $closeSearchForm = true;
        if( $request->ajax() ) {
            $closeSearchForm = false;
            return view('site.public_consultations.list_report', compact('filter','items', 'rf', 'closeSearchForm'));
        }

        $pageTitle = __('site.menu.public_consultation');
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_PC.'_'.app()->getLocale())->first();
        $this->setBreadcrumbsFull(array(
            ['name' => trans_choice('custom.public_consultations', 2), 'url' => route('public_consultation.index')],
            ['name' => trans_choice('custom.reportss', 2), 'url' => ''],
            ['name' => __('custom.pc_reports.standard'), 'url' => ''],
        ));
        return $this->view('site.public_consultations.report', compact('filter', 'items', 'pageTitle', 'pageTopContent', 'defaultOrderBy', 'defaultDirection', 'closeSearchForm'));
    }

    private function filtersReport($request){
        return array(
            'level' => array(
                'type' => 'select',
                'options' => enumToSelectOptions(InstitutionCategoryLevelEnum::options(), 'nomenclature_level', true),
                'multiple' => true,
                'default' => '',
                'label' => __('custom.pc_institution_level'),
                'value' => $request->input('level'),
                'col' => 'col-md-12'
            ),
            'fieldOfActions' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList(true, FieldOfAction::CATEGORY_NATIONAL), false),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.field_of_actions', 2),
                'value' => $request->input('fieldOfActions'),
                'col' => 'col-md-4',
            ),
            'areas' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList(true, FieldOfAction::CATEGORY_AREA), false),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.areas', 2),
                'value' => $request->input('areas'),
                'col' => 'col-md-4'
            ),
            'municipalities' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList(true, FieldOfAction::CATEGORY_MUNICIPAL), false),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.municipalitys', 2),
                'value' => $request->input('municipalities'),
                'col' => 'col-md-4'
            ),
            'name' => array(
                'type' => 'text',
                'label' => __('validation.attributes.name'),
                'value' => $request->input('name'),
                'col' => 'col-md-4'
            ),
            'consultationNumber' => array(
                'type' => 'text',
                'label' => __('custom.consultation_number_'),
                'value' => $request->input('consultationNumber'),
                'col' => 'col-md-4'
            ),
//            'fieldOfActions' => array(
//                'type' => 'select',
//                'options' => optionsFromModel(FieldOfAction::optionsList()),
//                'multiple' => true,
//                'default' => '',
//                'label' => trans_choice('custom.field_of_actions', 1),
//                'value' => $request->input('fieldOfActions'),
//                'col' => 'col-md-4'
//            ),
            'openFrom' => array(
                'type' => 'datepicker',
                'value' => $request->input('openFrom'),
                'label' => __('custom.begin_date'),
                'col' => 'col-md-4'
            ),
            'openTo' => array(
                'type' => 'datepicker',
                'value' => $request->input('openTo'),
                'label' => __('custom.end_date'),
                'col' => 'col-md-4'
            ),
            'comments' => array(
                'type' => 'select',
                'options' => array(
                    ['value' => '', 'name' => ''],
                    ['value' => '1', 'name' => __('custom.has')],
                    ['value' => '2', 'name' => __('custom.no_has')],
                ),
                'multiple' => false,
                'default' => '',
                'label' => trans_choice('custom.comments', 2),
                'value' => $request->input('comments'),
                'col' => 'col-md-4'
            ),
            'hasShortTermsReasons' => array(
                'type' => 'select',
                'options' => array(
                    ['value' => '', 'name' => ''],
                    ['value' => '1', 'name' => __('custom.has')],
                    ['value' => '2', 'name' => __('custom.no_has')],
                ),
                'multiple' => false,
                'default' => '',
                'label' => __('site.public_consultation.short_term_motive_label'),
                'value' => $request->input('hasShortTermsReasons'),
                'col' => 'col-md-4'
            ),
            'commentReport' => array(
                'type' => 'select',
                'options' => array(
                    ['value' => '', 'name' => ''],
                    ['value' => '1', 'name' => __('custom.has')],
                    ['value' => '2', 'name' => __('custom.no_has')],
                ),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.pc_reports.standard.comment_report'),
                'value' => $request->input('commentReport'),
                'col' => 'col-md-4'
            ),
            'actTypes' => array(
                'type' => 'select',
                'options' => optionsFromModel(ActType::optionsList()),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.act_type', 1),
                'value' => $request->input('actTypes'),
                'col' => 'col-md-4'
            ),
            'importers' => array(
                'type' => 'subjects',
                'label' => __('site.public_consultation.importer_pc'),
                'multiple' => true,
                'options' => optionsFromModel(Institution::simpleOptionsList(), true, '', __('site.public_consultation.importer')),
                'value' => request()->filled('importers') && sizeof(request()->input('importers')) ?  explode(',', request()->input('importers')[0]) : request()->input('importers'),
                'default' => '',
                'col' => 'col-md-4'
            ),
            'status' => array(
                'type' => 'select',
                'label' => __('custom.status'),
                'multiple' => false,
                'options' => array(
                    ['name' => __('custom.all'), 'value' => ''],
                    ['name' => trans_choice('custom.active', 1), 'value' => '1'],
                    ['name' => trans_choice('custom.inactive_m', 1), 'value' => '2'],
                ),
                'value' => request()->input('status'),
                'default' => '',
                'col' => 'col-md-4'
            ),
            'formGroup' => array(
                'title' => __('custom.pc_reports.standard.days'),
                'class' => 'mb-4 row',
                'fields' => array(
                    'days' => array(
                        'type' => 'select',
                        'options' => array(
                            ['value' => '', 'name' => ''],
                            ['value' => '1', 'name' => __('custom.pc_reports.standard.days_limit_period')],
                            ['value' => '2', 'name' => __('custom.pc_reports.standard.days_more_then_min')],
                        ),
                        'multiple' => false,
                        'default' => '',
                        'label' => __('custom.pc_reports.standard.days_defined'),
                        'value' => $request->input('days'),
                        'col' => 'col-md-4'
                    ),
                    'daysFrom' => array(
                        'type' => 'text',
                        'label' => __('custom.from'),
                        'value' => $request->input('daysFrom'),
                        'col' => 'col-md-2'
                    ),
                    'daysTo' => array(
                        'type' => 'text',
                        'label' => __('custom.to'),
                        'value' => $request->input('daysTo'),
                        'col' => 'col-md-2'
                    ),
                )
            ),
            'missingDocuments' => array(
                'type' => 'select',
                'label' => __('custom.pc_reports.missing_documents'),
                'multiple' => true,
                'options' => DocTypesEnum::pcMissingDocTypesSelect(),
                'value' => request()->input('missingDocuments'),
                'default' => '',
                'col' => 'col-md-4'
            ),
            'paginate' => array(
                'type' => 'select',
                'options' => paginationSelect(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.filter_pagination'),
                'value' => $request->input('paginate') ?? config('app.default_paginate'),
                'col' => 'col-md-s4'
            ),
        );
    }

    private function sortersReport()
    {
        return array(
            'regNum' => ['class' => 'col-md-2', 'label' => __('custom.number')],
            'actType' => ['class' => 'col-md-3', 'label' => __('site.public_consultation.type_consultation')],
            'fieldOfAction' => ['class' => 'col-md-3', 'label' => trans_choice('custom.field_of_actions', 1)],
            'title' => ['class' => 'col-md-2', 'label' => __('custom.title')],
            'date' => ['class' => 'col-md-2', 'label' => __('custom.date')],
        );
    }

    public function fieldОfАctionsReport(Request $request)
    {
        $rf = $request->all();
        $requestFilter = $request->all();
        $filter = $this->filtersFaReport($request, $rf);
        //Sorter
        //$sorter = $this->sortersReport();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'date';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');
        $paginate = $requestFilter['paginate'] ?? config('app.default_paginate');

        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        $fieldOfActions = isset($requestFilter['fieldOfActions']) && sizeof($requestFilter['fieldOfActions']) ? explode(',', $requestFilter['fieldOfActions'][0]) : null;
        $fieldOfActionsAreas = isset($requestFilter['areas']) && sizeof($requestFilter['areas']) ? explode(',', $requestFilter['areas'][0]) : null;
        $fieldOfActionsMunicipalities = isset($requestFilter['municipalities']) && sizeof($requestFilter['municipalities']) ? explode(',', $requestFilter['municipalities'][0]) : null;
        $levels = isset($requestFilter['level']) && sizeof($requestFilter['level']) ? explode(',', $requestFilter['level'][0]) : null;
        $openForm = isset($requestFilter['openFrom']) ? Carbon::parse($requestFilter['openFrom'])->format('Y-m-d') : null;
        $openTo = isset($requestFilter['openTo']) ? Carbon::parse($requestFilter['openTo'])->format('Y-m-d') : null;

        $q = DB::table('field_of_actions')
            ->select(['field_of_action_translations.name', DB::raw('count(distinct(public_consultation.id)) as pc_cnt'), DB::raw('max(field_of_actions.parentid) as fa_level')])
            ->join('field_of_action_translations', function ($j){
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('public_consultation', function ($q){
                $q->on('public_consultation.field_of_actions_id', '=', 'field_of_actions.id')
                    ->whereNull('public_consultation.deleted_at');
//                    ->where('public_consultation.open_from', '<=', Carbon::now()->format('Y-m-d'))
//                    ->where('public_consultation.active');
            })
            ->where('field_of_actions.active', '=', 1)
            ->whereNull('field_of_actions.deleted_at')
            ->when($fieldOfActions, function ($q) use($fieldOfActions){
                $q->whereIn('field_of_actions.id', $fieldOfActions);
            })
            ->when($fieldOfActionsAreas, function ($q) use($fieldOfActionsAreas){
                $q->whereIn('field_of_actions.id', $fieldOfActionsAreas);
            })
            ->when($fieldOfActionsMunicipalities, function ($q) use($fieldOfActionsMunicipalities){
                $q->whereIn('field_of_actions.id', $fieldOfActionsMunicipalities);
            })
            ->when($levels, function ($q) use($levels){
                //$q->whereIn('public_consultation.consultation_level_id', $levels);
                $faLevels = [];
                foreach ($levels as $l){
                    $faLevels[] = InstitutionCategoryLevelEnum::fieldOfActionCategory((int)$l);
                }
                $q->whereIn('field_of_actions.parentid', $faLevels);
            })
            ->when($openForm, function ($q) use($openForm){
                $q->where('public_consultation.open_from', '>=', $openForm);
            })->when($openTo, function ($q) use($openTo){
                $q->where('public_consultation.open_to', '<=', $openTo);
            })
            ->where('field_of_actions.parentid', '<>', 0)
            ->groupBy('field_of_action_translations.id');

        if($request->input('export_excel') || $request->input('export_pdf')){
            $items = $q->get();
            $exportData = [
                'title' => __('custom.pc_report_title') .' - '.trans_choice('custom.field_of_actions', 2),
                'rows' => $items,
            ];

            $fileName = 'pc_report_'.Carbon::now()->format('Y_m_d_H_i_s');
            if($request->input('export_pdf')){
                ini_set('max_execution_time', 60);
                $pdf = PDF::loadView('exports.pc_report_fa', ['data' => $exportData, 'isPdf' => true])->setPaper('a4', 'landscape');
                return $pdf->download($fileName.'.pdf');
            } else{
                return Excel::download(new PublicConsultationFaReportExport($exportData), $fileName.'.xlsx');
            }
        } else{
            $items = $q->paginate($paginate);
        }

        $closeSearchForm = true;
        if( $request->ajax() ) {
            $closeSearchForm = false;
            return view('site.public_consultations.list_report_fa', compact('filter','items', 'rf', 'closeSearchForm'));
        }

        $pageTitle = __('site.menu.public_consultation');
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_PC.'_'.app()->getLocale())->first();
        $this->setBreadcrumbsFull(array(
            ['name' => trans_choice('custom.public_consultations', 2), 'url' => route('public_consultation.index')],
            ['name' => trans_choice('custom.reportss', 2), 'url' => ''],
            ['name' => __('custom.pc_reports.field_of_action'), 'url' => ''],
        ));
        return $this->view('site.public_consultations.report_fa', compact('filter', 'items', 'pageTitle', 'pageTopContent', 'defaultOrderBy', 'defaultDirection', 'closeSearchForm'));
    }

    private function filtersFaReport($request){
        return array(
            'level' => array(
                'type' => 'select',
                'options' => enumToSelectOptions(InstitutionCategoryLevelEnum::options(), 'nomenclature_level', true),
                'multiple' => true,
                'default' => '',
                'label' => __('custom.pc_institution_level'),
                'value' => $request->input('level'),
                'col' => 'col-md-12'
            ),
            'fieldOfActions' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList(true, FieldOfAction::CATEGORY_NATIONAL), false),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.field_of_actions', 2),
                'value' => $request->input('fieldOfActions'),
                'col' => 'col-md-4',
            ),
            'areas' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList(true, FieldOfAction::CATEGORY_AREA), false),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.areas', 2),
                'value' => $request->input('areas'),
                'col' => 'col-md-4'
            ),
            'municipalities' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList(true, FieldOfAction::CATEGORY_MUNICIPAL), false),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.municipalitys', 2),
                'value' => $request->input('municipalities'),
                'col' => 'col-md-4'
            ),
            'openFrom' => array(
                'type' => 'datepicker',
                'value' => $request->input('openFrom'),
                'label' => __('custom.begin_date'),
                'col' => 'col-md-4'
            ),
            'openTo' => array(
                'type' => 'datepicker',
                'value' => $request->input('openTo'),
                'label' => __('custom.end_date'),
                'col' => 'col-md-4'
            ),
            'paginate' => array(
                'type' => 'select',
                'options' => paginationSelect(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.filter_pagination'),
                'value' => $request->input('paginate') ?? config('app.default_paginate'),
                'col' => 'col-md-s4'
            ),
        );
    }

    public function fieldОfАctionsInstitutionReport(Request $request)
    {
        $rf = $request->all();
        $requestFilter = $request->all();
        $filter = $this->filtersFaInstitutionReport($request, $rf);
        //Sorter
        //$sorter = $this->sortersReport();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'date';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');
        $paginate = $requestFilter['paginate'] ?? config('app.default_paginate');

        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        $openForm = isset($requestFilter['openFrom']) ? Carbon::parse($requestFilter['openFrom'])->format('Y-m-d') : null;
        $openTo = isset($requestFilter['openTo']) ? Carbon::parse($requestFilter['openTo'])->format('Y-m-d') : null;
        $fieldOfActions = isset($requestFilter['fieldOfActions']) && sizeof($requestFilter['fieldOfActions']) ? explode(',', $requestFilter['fieldOfActions'][0]) : null;
        $fieldOfActionsAreas = isset($requestFilter['areas']) && sizeof($requestFilter['areas']) ? explode(',', $requestFilter['areas'][0]) : null;
        $fieldOfActionsMunicipalities = isset($requestFilter['municipalities']) && sizeof($requestFilter['municipalities']) ? explode(',', $requestFilter['municipalities'][0]) : null;
        $levels = isset($requestFilter['level']) && sizeof($requestFilter['level']) ? explode(',', $requestFilter['level'][0]) : null;

        $q = DB::table('public_consultation')
            ->select(['institution_translations.name', DB::raw('count(distinct(public_consultation.id)) as pc_cnt')])
//            ->join('field_of_action_translations', function ($j){
//                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
//                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
//            })
            ->join('field_of_actions', function ($q){
                $q->on('public_consultation.field_of_actions_id', '=', 'field_of_actions.id');
            })
            ->join('institution', 'institution.id', '=', 'public_consultation.importer_institution_id')
            ->join('institution_translations', function ($j){
                $j->on('institution_translations.institution_id', '=', 'institution.id')
                    ->where('institution_translations.locale', '=', app()->getLocale());
            })
            ->when($fieldOfActions, function($q) use($fieldOfActions){
                $q->whereIn('public_consultation.field_of_actions_id', $fieldOfActions);
            })
            ->when($fieldOfActionsAreas, function ($q) use($fieldOfActionsAreas){
                $q->whereIn('public_consultation.field_of_actions_id', $fieldOfActionsAreas);
            })
            ->when($fieldOfActionsMunicipalities, function ($q) use($fieldOfActionsMunicipalities){
                $q->whereIn('public_consultation.field_of_actions_id', $fieldOfActionsMunicipalities);
            })
            ->when($levels, function ($q) use($levels){
//                $q->whereIn('public_consultation.consultation_level_id', $levels);
                $faLevels = [];
                foreach ($levels as $l){
                    $faLevels[] = InstitutionCategoryLevelEnum::fieldOfActionCategory((int)$l);
                }
                $q->whereIn('field_of_actions.parentid', $faLevels);
            })
            ->when($openForm, function ($q) use($openForm){
                $q->where('public_consultation.open_from', '>=', $openForm);
            })->when($openTo, function ($q) use($openTo){
                $q->where('public_consultation.open_to', '<=', $openTo);
            })
            ->where('field_of_actions.active', '=', 1)
            ->whereNull('field_of_actions.deleted_at')
            ->whereNull('public_consultation.deleted_at')
            ->where('public_consultation.active', '=', 1)
            ->where('institution.id', '<>', env('DEFAULT_INSTITUTION_ID'))
            ->groupBy('institution_translations.id');

        if($request->input('export_excel') || $request->input('export_pdf')){
            $items = $q->get();
            $exportData = [
                'title' => __('custom.pc_report_title') .' - '.trans_choice('custom.field_of_actions', 2).' ('.trans_choice('custom.institutions', 2).')',
                'rows' => $items,
            ];

            $fileName = 'pc_report_'.Carbon::now()->format('Y_m_d_H_i_s');
            if($request->input('export_pdf')){
                ini_set('max_execution_time', 60);
                $pdf = PDF::loadView('exports.pc_report_fa_institution', ['data' => $exportData, 'isPdf' => true])->setPaper('a4', 'landscape');
                return $pdf->download($fileName.'.pdf');
            } else{
                return Excel::download(new PublicConsultationFaReportExport($exportData), $fileName.'.xlsx');
            }
        } else{
            $items = $q->paginate($paginate);
        }

        $closeSearchForm = true;
        if( $request->ajax() ) {
            $closeSearchForm = false;
            return view('site.public_consultations.list_report_fa_institution', compact('filter','items', 'rf'. 'closeSearchForm'));
        }
//dd($items);
        $pageTitle = __('site.menu.public_consultation');
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_PC.'_'.app()->getLocale())->first();
        $this->setBreadcrumbsFull(array(
            ['name' => trans_choice('custom.public_consultations', 2), 'url' => route('public_consultation.index')],
            ['name' => trans_choice('custom.reportss', 2), 'url' => ''],
            ['name' => __('custom.pc_reports.field_of_action_institution'), 'url' => ''],
        ));
        return $this->view('site.public_consultations.report_fa_institution', compact('filter', 'items', 'pageTitle', 'pageTopContent', 'defaultOrderBy', 'defaultDirection', 'closeSearchForm'));
    }

    private function filtersFaInstitutionReport($request){
        return array(
            'level' => array(
                'type' => 'select',
                'options' => enumToSelectOptions(InstitutionCategoryLevelEnum::options(), 'nomenclature_level', true),
                'multiple' => true,
                'default' => '',
                'label' => __('custom.pc_institution_level'),
                'value' => $request->input('level'),
                'col' => 'col-md-12'
            ),
            'fieldOfActions' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList(true, FieldOfAction::CATEGORY_NATIONAL), false),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.field_of_actions', 2),
                'value' => $request->input('fieldOfActions'),
                'col' => 'col-md-4',
            ),
            'areas' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList(true, FieldOfAction::CATEGORY_AREA), false),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.areas', 2),
                'value' => $request->input('areas'),
                'col' => 'col-md-4'
            ),
            'municipalities' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList(true, FieldOfAction::CATEGORY_MUNICIPAL), false),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.municipalitys', 2),
                'value' => $request->input('municipalities'),
                'col' => 'col-md-4'
            ),
            'openFrom' => array(
                'type' => 'datepicker',
                'value' => $request->input('openFrom'),
                'label' => __('custom.begin_date'),
                'col' => 'col-md-4'
            ),
            'openTo' => array(
                'type' => 'datepicker',
                'value' => $request->input('openTo'),
                'label' => __('custom.end_date'),
                'col' => 'col-md-4'
            ),
            'paginate' => array(
                'type' => 'select',
                'options' => paginationSelect(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.filter_pagination'),
                'value' => $request->input('paginate') ?? config('app.default_paginate'),
                'col' => 'col-md-s4'
            ),
        );
    }


    public function institutionsReport(Request $request)
    {
        $rf = $request->all();
        $requestFilter = $request->all();
        $filter = $this->filtersInstitutionReport($request, $rf);
        //Sorter
        //$sorter = $this->sortersReport();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'date';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');
        $paginate = $requestFilter['paginate'] ?? config('app.default_paginate');

        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        $openForm = isset($requestFilter['openFrom']) ? Carbon::parse($requestFilter['openFrom'])->format('Y-m-d') : null;
        $openTo = isset($requestFilter['openTo']) ? Carbon::parse($requestFilter['openTo'])->format('Y-m-d') : null;
        $institutions = isset($requestFilter['institution']) && sizeof($requestFilter['institution']) ? explode(',', $requestFilter['institution'][0]) : null;
        $fieldOfActions = isset($requestFilter['fieldOfActions']) && sizeof($requestFilter['fieldOfActions']) ? explode(',', $requestFilter['fieldOfActions'][0]) : null;
        $fieldOfActionsAreas = isset($requestFilter['areas']) && sizeof($requestFilter['areas']) ? explode(',', $requestFilter['areas'][0]) : null;
        $fieldOfActionsMunicipalities = isset($requestFilter['municipalities']) && sizeof($requestFilter['municipalities']) ? explode(',', $requestFilter['municipalities'][0]) : null;
        $levels = isset($requestFilter['level']) && sizeof($requestFilter['level']) ? explode(',', $requestFilter['level'][0]) : null;

        //Колко са консултациите по вид на обекта на консултации групирано по Институции
        $consultationsByActType = array();
        $qConsultationsByActType = DB::select('
            select
                A.institution_id,
                jsonb_agg(jsonb_build_object(\'act_name\', A.act_name, \'act_cnt\', A.act_cnt)) as act_info
            from (
                select
                    public_consultation.importer_institution_id as institution_id,
                    act_type.id as act_id,
                    count(act_type.id) as act_cnt,
                    max(act_type_translations.name) as act_name
                from public_consultation
                join act_type on act_type.id = public_consultation.act_type_id
                join act_type_translations on act_type_translations.act_type_id = act_type.id and act_type_translations.locale = \''.app()->getLocale().'\'
                '.($openForm ? ' and public_consultation.open_from >= \''.$openForm.'\'' : '').'
                '.($openTo ? ' and public_consultation.open_to <= \''.$openTo.'\'' : '').'
                '.($institutions ? ' and public_consultation.importer_institution_id in ('. implode(',',$institutions) .')' : '').'
                '.($levels ? ' and public_consultation.consultation_level_id in ('. implode(',',$levels) .')' : '').'
                '.($fieldOfActions ? ' and public_consultation.field_of_actions_id in ('. implode(',',$fieldOfActions) .')' : '').'
                '.($fieldOfActionsAreas ? ' and public_consultation.field_of_actions_id in ('. implode(',',$fieldOfActionsAreas) .')' : '').'
                '.($fieldOfActionsMunicipalities ? ' and public_consultation.field_of_actions_id in ('. implode(',',$fieldOfActionsMunicipalities) .')' : '').'
                group by public_consultation.importer_institution_id, act_type.id
            ) A
            group by A.institution_id
        ');
        if(sizeof($qConsultationsByActType)){
            $consultationsByActType = array_combine(array_column($qConsultationsByActType, 'institution_id'), $qConsultationsByActType);
        }
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
                '.($openForm ? ' and public_consultation.open_from >= \''.$openForm.'\'' : '').'
                '.($openTo ? ' and public_consultation.open_to <= \''.$openTo.'\'' : '').'
                '.($institutions ? ' and public_consultation.importer_institution_id in ('. implode(',',$institutions) .')' : '').'
                '.($levels ? ' and public_consultation.consultation_level_id in ('. implode(',',$levels) .')' : '').'
                '.($fieldOfActions ? ' and public_consultation.field_of_actions_id in ('. implode(',',$fieldOfActions) .')' : '').'
                '.($fieldOfActionsAreas ? ' and public_consultation.field_of_actions_id in ('. implode(',',$fieldOfActionsAreas) .')' : '').'
                '.($fieldOfActionsMunicipalities ? ' and public_consultation.field_of_actions_id in ('. implode(',',$fieldOfActionsMunicipalities) .')' : '').'
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
                DB::raw('sum(case when (public_consultation.open_to - public_consultation.open_from) < 30 then 1 else 0 end) as less_days_cnt'),
                DB::raw('sum(case when (public_consultation.open_to - public_consultation.open_from) < 30 and (public_consultation_translations.short_term_reason is null or public_consultation_translations.short_term_reason = \'\') then 1 else 0 end) as no_less_days_reason_cnt'),
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
            ->when($institutions, function($q) use($institutions){
                $q->whereIn('public_consultation.importer_institution_id', $institutions);
            })
            ->when($fieldOfActions, function($q) use($fieldOfActions){
                $q->whereIn('public_consultation.field_of_actions_id', $fieldOfActions);
            })
            ->when($fieldOfActionsAreas, function ($q) use($fieldOfActionsAreas){
                $q->whereIn('public_consultation.field_of_actions_id', $fieldOfActionsAreas);
            })
            ->when($fieldOfActionsMunicipalities, function ($q) use($fieldOfActionsMunicipalities){
                $q->whereIn('public_consultation.field_of_actions_id', $fieldOfActionsMunicipalities);
            })
            ->when($levels, function ($q) use($levels){
                $q->whereIn('public_consultation.consultation_level_id', $levels);
            })
            ->when($openForm, function ($q) use($openForm){
                $q->where('public_consultation.open_from', '>=', $openForm);
            })->when($openTo, function ($q) use($openTo){
                $q->where('public_consultation.open_to', '<=', $openTo);
            })
            ->whereNull('public_consultation.deleted_at')
            ->where('public_consultation.active', '=', 1)
            ->where('institution.id', '<>', env('DEFAULT_INSTITUTION_ID'))
            ->groupBy('institution.id');

        if($request->input('export_excel') || $request->input('export_pdf')){
            $items = $q->get();
            $exportData = [
                'title' => __('custom.pc_report_title').' ('.trans_choice('custom.institutions', 2).')',
                'rows' => $items,
                'consultationByType' => $consultationsByActType,
                'missingFiles' => $missingFiles
            ];

            $fileName = 'pc_report_'.Carbon::now()->format('Y_m_d_H_i_s');
            if($request->input('export_pdf')){
                ini_set('max_execution_time', 60);
                $pdf = PDF::loadView('exports.pc_report_institution', ['data' => $exportData, 'isPdf' => true])->setPaper('a4', 'landscape');
                return $pdf->download($fileName.'.pdf');
            } else{
                return Excel::download(new PublicConsultationInstitutionReportExport($exportData), $fileName.'.xlsx');
            }
        } else{
            $items = $q->paginate($paginate);
        }

        $closeSearchForm = true;
        if( $request->ajax() ) {
            $closeSearchForm = false;
            return view('site.public_consultations.list_report_institution', compact('filter','items', 'rf', 'consultationsByActType', 'missingFiles', 'closeSearchForm'));
        }
        $pageTitle = __('site.menu.public_consultation');
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_PC.'_'.app()->getLocale())->first();
        $this->setBreadcrumbsFull(array(
            ['name' => trans_choice('custom.public_consultations', 2), 'url' => route('public_consultation.index')],
            ['name' => trans_choice('custom.reportss', 2), 'url' => ''],
            ['name' => __('custom.pc_reports.institutions'), 'url' => ''],
        ));
        return $this->view('site.public_consultations.report_institution', compact('filter', 'items', 'pageTitle', 'pageTopContent', 'defaultOrderBy', 'defaultDirection', 'consultationsByActType', 'missingFiles', 'closeSearchForm'));
    }

    public function filtersInstitutionReport($request){
        return array(
            'level' => array(
                'type' => 'select',
                'options' => enumToSelectOptions(InstitutionCategoryLevelEnum::options(), 'nomenclature_level', true),
                'multiple' => true,
                'default' => '',
                'label' => __('custom.pc_institution_level'),
                'value' => $request->input('level'),
                'col' => 'col-md-12'
            ),
            'fieldOfActions' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList(true, FieldOfAction::CATEGORY_NATIONAL), false),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.field_of_actions', 2),
                'value' => $request->input('fieldOfActions'),
                'col' => 'col-md-4',
            ),
            'areas' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList(true, FieldOfAction::CATEGORY_AREA), false),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.areas', 2),
                'value' => $request->input('areas'),
                'col' => 'col-md-4'
            ),
            'municipalities' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList(true, FieldOfAction::CATEGORY_MUNICIPAL), false),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.municipalitys', 2),
                'value' => $request->input('municipalities'),
                'col' => 'col-md-4'
            ),
            'institution' => array(
                'type' => 'subjects',
                'label' => __('site.public_consultation.importer'),
                'multiple' => true,
                'options' => optionsFromModel(Institution::simpleOptionsList(), true, '', __('custom.all')),
                'value' => request()->filled('institution') && sizeof(request()->input('institution')) ?  explode(',', request()->input('institution')[0]) : request()->input('institution'),
                'default' => '',
                'col' => 'col-md-4'
            ),
            'openFrom' => array(
                'type' => 'datepicker',
                'value' => $request->input('openFrom'),
                'label' => __('custom.begin_date'),
                'col' => 'col-md-4'
            ),
            'openTo' => array(
                'type' => 'datepicker',
                'value' => $request->input('openTo'),
                'label' => __('custom.end_date'),
                'col' => 'col-md-4'
            ),
            'paginate' => array(
                'type' => 'select',
                'options' => paginationSelect(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.filter_pagination'),
                'value' => $request->input('paginate') ?? config('app.default_paginate'),
                'col' => 'col-md-s4'
            )
        );
    }

    private function sorters()
    {
        return array(
            'regNum' => ['class' => 'col-md-2', 'label' => __('custom.number')],
            'actType' => ['class' => 'col-md-3', 'label' => __('site.public_consultation.type_consultation')],
            'fieldOfAction' => ['class' => 'col-md-3', 'label' => trans_choice('custom.field_of_actions', 1)],
            'title' => ['class' => 'col-md-2', 'label' => __('custom.title')],
            'date' => ['class' => 'col-md-2', 'label' => __('custom.date')],
        );
    }

    private function filters($request)
    {
//        $fields = FieldOfAction::select('field_of_actions.*')
//            ->with('translations')
//            ->joinTranslation(FieldOfAction::class)
//            ->whereLocale(app()->getLocale())
//            ->orderBy('field_of_action_translations.name', 'asc')
//            ->get();
        return array(
            'level' => array(
                'type' => 'select',
                'options' => enumToSelectOptions(InstitutionCategoryLevelEnum::options(), 'nomenclature_level', true),
                'multiple' => true,
                'default' => '',
                'label' => __('custom.pc_institution_level'),
                'value' => $request->input('level'),
                'col' => 'col-md-12'
            ),
            'fieldOfActions' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList(true, FieldOfAction::CATEGORY_NATIONAL), false),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.field_of_actions', 2),
                'value' => $request->input('fieldOfActions'),
                'col' => 'col-md-4',
            ),
            'areas' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList(true, FieldOfAction::CATEGORY_AREA), false),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.areas', 2),
                'value' => $request->input('areas'),
                'col' => 'col-md-4'
            ),
            'municipalities' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList(true, FieldOfAction::CATEGORY_MUNICIPAL), false),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.municipalitys', 2),
                'value' => $request->input('municipalities'),
                'col' => 'col-md-4'
            ),
            'name' => array(
                'type' => 'text',
                'label' => __('validation.attributes.name'),
                'value' => $request->input('name'),
                'col' => 'col-md-4'
            ),
            'consultationNumber' => array(
                'type' => 'text',
                'label' => __('custom.consultation_number_'),
                'value' => $request->input('consultationNumber'),
                'col' => 'col-md-4'
            ),
            'openFrom' => array(
                'type' => 'datepicker',
                'value' => $request->input('openFrom'),
                'label' => __('custom.begin_date'),
                'col' => 'col-md-4'
            ),
            'openTo' => array(
                'type' => 'datepicker',
                'value' => $request->input('openTo'),
                'label' => __('custom.end_date'),
                'col' => 'col-md-4'
            ),
            'actTypes' => array(
                'type' => 'select',
                'options' => optionsFromModel(ActType::optionsList()),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.act_type', 1),
                'value' => $request->input('actTypes'),
                'col' => 'col-md-4'
            ),
//            'levels' => array(
//                'type' => 'select',
//                'options' => enumToSelectOptions(InstitutionCategoryLevelEnum::options(), 'nomenclature_level', true),
//                'multiple' => true,
//                'default' => '',
//                'label' => __('site.public_consultation.importer_type'),
//                'value' => $request->input('levels'),
//                'col' => 'col-md-4'
//            ),
            'importers' => array(
                'type' => 'subjects',
                'label' => __('site.public_consultation.importer_pc'),
                'multiple' => true,
                'options' => optionsFromModel(Institution::simpleOptionsList(), true, '', __('site.public_consultation.importer')),
                'value' => request()->input('importers'),
                'default' => '',
                'col' => 'col-md-4'
            ),
            'paginate' => array(
                'type' => 'select',
                'options' => paginationSelect(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.filter_pagination'),
                'value' => $request->input('paginate') ?? config('app.default_paginate'),
                'col' => 'col-md-s4'
            ),
        );
    }
}
