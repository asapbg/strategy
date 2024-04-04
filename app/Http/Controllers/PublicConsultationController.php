<?php

namespace App\Http\Controllers;

use App\Enums\InstitutionCategoryLevelEnum;
use App\Exports\PublicConsultationFaReportExport;
use App\Exports\PublicConsultationReportExport;
use App\Http\Requests\StoreCommentRequest;
use App\Models\ActType;
use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
use App\Models\FieldOfAction;
use App\Models\Setting;
use App\Models\StrategicDocuments\Institution;
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
        if( $request->ajax() ) {
            return view('site.public_consultations.list', compact('filter','sorter', 'pk', 'rf'));
        }

        $pageTitle = __('site.menu.public_consultation');
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_PC.'_'.app()->getLocale())->first();
        return $this->view('site.public_consultations.index', compact('filter', 'sorter', 'pk', 'pageTitle', 'pageTopContent', 'defaultOrderBy', 'defaultDirection'));
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
        $this->setSeo($item->title);
        return $this->view('site.public_consultations.view', compact('item', 'pageTitle', 'documents', 'timeline', 'pageTopContent', 'documentsImport'));
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
        //Sorter
        //$sorter = $this->sortersReport();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'date';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');
        $paginate = $requestFilter['paginate'] ?? config('app.default_paginate');

        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        $q = PublicConsultation::select('public_consultation.*')
            ->ActivePublic()
            ->with(['translation', 'comments', 'fieldOfAction', 'fieldOfAction.translations',
                'actType', 'actType.translations',
                'importerInstitution', 'importerInstitution.translations', 'comments', 'proposalReport'])
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
            ->SortedBy($sort,$sortOrd);

        if($request->input('export_excel') || $request->input('export_pdf')){
            $items = $q->get();
            $exportData = [
                'title' => __('custom.pc_report_title'),
                'rows' => $items,
            ];

            $fileName = 'pc_report_'.Carbon::now()->format('Y_m_d_H_i_s');
            if($request->input('export_pdf')){
                ini_set('max_execution_time', 60);
                $pdf = PDF::loadView('exports.pc_report', ['data' => $exportData, 'isPdf' => true])->setPaper('a4', 'landscape');
                return $pdf->download($fileName.'.pdf');
            } else{
                return Excel::download(new PublicConsultationReportExport($exportData), $fileName.'.xlsx');
            }
        } else{
            $items = $q->paginate($paginate);
        }

        if( $request->ajax() ) {
            return view('site.public_consultations.list_report', compact('filter','items', 'rf'));
        }

        $pageTitle = __('site.menu.public_consultation');
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_PC.'_'.app()->getLocale())->first();
        return $this->view('site.public_consultations.report', compact('filter', 'items', 'pageTitle', 'pageTopContent', 'defaultOrderBy', 'defaultDirection'));
    }

    private function filtersReport($request){
        $fields = FieldOfAction::select('field_of_actions.*')
            ->with('translations')
            ->joinTranslation(FieldOfAction::class)
            ->whereLocale(app()->getLocale())
            ->orderBy('field_of_action_translations.name', 'asc')
            ->get();
        return array(
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
            'fieldOfActions' => array(
                'type' => 'select',
                'options' => optionsFromModel($fields),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.field_of_actions', 1),
                'value' => $request->input('fieldOfActions'),
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
            'levels' => array(
                'type' => 'select',
                'options' => enumToSelectOptions(InstitutionCategoryLevelEnum::options(), 'nomenclature_level', true),
                'multiple' => true,
                'default' => '',
                'label' => __('site.public_consultation.importer_type'),
                'value' => $request->input('levels'),
                'col' => 'col-md-4'
            ),
            'importers' => array(
                'type' => 'subjects',
                'label' => __('site.public_consultation.importer'),
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

        $fieldOfActions = isset($requestFilter['fieldOfActions']) && sizeof($requestFilter['fieldOfActions']) ? $requestFilter['fieldOfActions'] : null;
        $q = DB::table('field_of_actions')
            ->select(['field_of_action_translations.name', DB::raw('count(distinct(public_consultation.id)) as pc_cnt')])
            ->join('field_of_action_translations', function ($j){
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('public_consultation', function ($q){
                $q->on('public_consultation.field_of_actions_id', '=', 'field_of_actions.id')
                    ->whereNull('public_consultation.deleted_at')->where('public_consultation.active')
                    ->where('public_consultation.open_from', '<=', Carbon::now()->format('Y-m-d'));
            })
            ->where('field_of_actions.active', '=', 1)
            ->whereNull('field_of_actions.deleted_at')
            ->when($fieldOfActions, function ($q) use($fieldOfActions){
                $q->whereIn('field_of_actions.id', $fieldOfActions);
            })
            ->groupBy('field_of_action_translations.id');

        if($request->input('export_excel') || $request->input('export_pdf')){
            $items = $q->get();
            $exportData = [
                'title' => __('custom.pc_report_title'),
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

        if( $request->ajax() ) {
            return view('site.public_consultations.list_report_fa', compact('filter','items', 'rf'));
        }

        $pageTitle = __('site.menu.public_consultation');
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_PC.'_'.app()->getLocale())->first();
        return $this->view('site.public_consultations.report_fa', compact('filter', 'items', 'pageTitle', 'pageTopContent', 'defaultOrderBy', 'defaultDirection'));
    }

    private function filtersFaReport($request){
        $fields = FieldOfAction::select('field_of_actions.*')
            ->with('translations')
            ->joinTranslation(FieldOfAction::class)
            ->whereLocale(app()->getLocale())
            ->orderBy('field_of_action_translations.name', 'asc')
            ->get();
        return array(
            'fieldOfActions' => array(
                'type' => 'select',
                'options' => optionsFromModel($fields),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.field_of_actions', 1),
                'value' => $request->input('fieldOfActions'),
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

        $fieldOfAction = isset($requestFilter['fieldOfAction']) && !empty($requestFilter['fieldOfAction']) ? $requestFilter['fieldOfAction'] : null;

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
            ->when($fieldOfAction, function($q) use($fieldOfAction){
                $q->where('public_consultation.field_of_actions_id', '=', $fieldOfAction);
            })
            ->where('field_of_actions.active', '=', 1)
            ->whereNull('field_of_actions.deleted_at')
            ->whereNull('public_consultation.deleted_at')
            ->where('public_consultation.active', '=', 1)
            ->where('public_consultation.open_from', '<=', Carbon::now()->format('Y-m-d'))
            ->where('institution.id', '<>', env('DEFAULT_INSTITUTION_ID'))
            ->groupBy('institution_translations.id');

        if($request->input('export_excel') || $request->input('export_pdf')){
            $items = $q->get();
            $exportData = [
                'title' => __('custom.pc_report_title'),
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

        if( $request->ajax() ) {
            return view('site.public_consultations.list_report_fa_institution', compact('filter','items', 'rf'));
        }
//dd($items);
        $pageTitle = __('site.menu.public_consultation');
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_PC.'_'.app()->getLocale())->first();
        return $this->view('site.public_consultations.report_fa_institution', compact('filter', 'items', 'pageTitle', 'pageTopContent', 'defaultOrderBy', 'defaultDirection'));
    }

    private function filtersFaInstitutionReport($request){
        $fields = FieldOfAction::select('field_of_actions.*')
            ->with('translations')
            ->joinTranslation(FieldOfAction::class)
            ->whereLocale(app()->getLocale())
            ->orderBy('field_of_action_translations.name', 'asc')
            ->get();
        return array(
            'fieldOfAction' => array(
                'type' => 'select',
                'options' => optionsFromModel($fields, true),
                'default' => '',
                'label' => trans_choice('custom.field_of_actions', 1),
                'value' => $request->input('fieldOfAction'),
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
        echo 'sdff';
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
        $fields = FieldOfAction::select('field_of_actions.*')
            ->with('translations')
            ->joinTranslation(FieldOfAction::class)
            ->whereLocale(app()->getLocale())
            ->orderBy('field_of_action_translations.name', 'asc')
            ->get();
        return array(
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
            'fieldOfActions' => array(
                'type' => 'select',
                'options' => optionsFromModel($fields),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.field_of_actions', 1),
                'value' => $request->input('fieldOfActions'),
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
            'levels' => array(
                'type' => 'select',
                'options' => enumToSelectOptions(InstitutionCategoryLevelEnum::options(), 'nomenclature_level', true),
                'multiple' => true,
                'default' => '',
                'label' => __('site.public_consultation.importer_type'),
                'value' => $request->input('levels'),
                'col' => 'col-md-4'
            ),
            'importers' => array(
                'type' => 'subjects',
                'label' => __('site.public_consultation.importer'),
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
