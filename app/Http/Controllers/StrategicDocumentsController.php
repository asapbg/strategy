<?php

namespace App\Http\Controllers;

use App\Enums\InstitutionCategoryLevelEnum;
use App\Exports\StrategicDocumentsExport;
use App\Models\AuthorityAcceptingStrategic;
use App\Models\CustomRole;
use App\Models\EkatteArea;
use App\Models\EkatteMunicipality;
use App\Models\FieldOfAction;
use App\Models\File;
use App\Models\Page;
use App\Models\PolicyArea;
use App\Models\Pris;
use App\Models\Setting;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentChildren;
use App\Models\StrategicDocumentFile;
use App\Models\StrategicDocumentLevel;
use App\Models\StrategicDocuments\Institution;
use App\Models\User;
use App\Models\UserSubscribe;
use App\Services\Exports\ExportService;
use App\Services\FileOcr;
use App\Services\StrategicDocuments\CommonService;
use App\Services\StrategicDocuments\FileService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Admin\StrategicDocumentsController as AdminStrategicDocumentsController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class StrategicDocumentsController extends Controller
{
    private $pageTitle;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->title_singular = trans_choice('custom.strategic_documents', 2);
        $this->pageTitle = trans_choice('custom.strategic_documents', 2);
    }

    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $editRouteName = AdminStrategicDocumentsController::EDIT_ROUTE;
        $deleteRouteName = AdminStrategicDocumentsController::DELETE_ROUTE;
        //Filter
        $rf = $request->all();
        $requestFilter = $request->all();
        if(empty($rf)){
            $requestFilter['title'] = null;
            $requestFilter['status'] = 'active';
        }
        $filter = $this->filters($request, $rf);
        //Sorter
        $sorter = $this->sorters();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'title';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');

        $paginate = $requestFilter['paginate'] ?? Pris::PAGINATE;
        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        if(isset($requestFilter['title']) && !empty($requestFilter['title'])){
            $requestFilter['text'] = $requestFilter['title'];
            unset($requestFilter['title']);
        }
        $items = StrategicDocument::list($requestFilter, $sort, $sortOrd, $paginate);
        if(isset($requestFilter['text'])){
            $requestFilter['title'] = $requestFilter['text'];
            unset($requestFilter['text']);
        }

        $hasSubscribeEmail = $this->hasSubscription(null, StrategicDocument::class, $requestFilter);
        $hasSubscribeRss = $this->hasSubscription(null, StrategicDocument::class, $requestFilter, UserSubscribe::CHANNEL_RSS);

        if( $request->ajax() ) {
            return view('site.strategic_documents.list', compact('filter','sorter', 'items',
                'rf', 'requestFilter', 'editRouteName', 'deleteRouteName', 'hasSubscribeEmail', 'hasSubscribeRss'));
        }

        $pageTitle = trans('custom.strategy_documents_plural');
        $this->composeBreadcrumbs(null, array(['name' => trans_choice('custom.table_view', 1), 'url' => '']));

        return $this->view('site.strategic_documents.index', compact('filter','sorter', 'items', 'pageTitle',
            'rf', 'requestFilter', 'defaultOrderBy', 'defaultDirection', 'editRouteName', 'deleteRouteName', 'hasSubscribeEmail', 'hasSubscribeRss'));
    }

    public function tree(Request $request)
    {
        $editRouteName = AdminStrategicDocumentsController::EDIT_ROUTE;
        $deleteRouteName = AdminStrategicDocumentsController::DELETE_ROUTE;
        $categories = isset($rf['level']) ? $rf['level'] : [InstitutionCategoryLevelEnum::CENTRAL->value, InstitutionCategoryLevelEnum::AREA->value, InstitutionCategoryLevelEnum::MUNICIPAL->value];
        $items = [];
        foreach ($categories as $cat){
            if(!isset($items[$cat])){
                $items[$cat] = ['items' => [], 'name' => __('custom.strategic_document.category.'.\App\Enums\InstitutionCategoryLevelEnum::keyByValue($cat))];
            }

            $items[$cat]['items'] = \DB::select('
                select
                    strategic_document.id as sd_id
                    ,max(strategic_document_translations.title) as sd_title
                    ,field_of_action_translations.name as sd_policy_title
                     ,field_of_actions.id as sd_policy_id
                    , max(children.id) as child_id
                    , max(children.strategic_document_id) as child_sd_id
                    , max(children.parent_id) as child_parent_id
                    , max(children.strategic_document_level_id) as child_doc_level
                    , max(children.policy_area_id) as child_policy_area
                    , max(children.title) as child_title
                    , max(children.depth) as child_depth
                    , max(children.path) as child_path
                from strategic_document
                join strategic_document_translations on strategic_document_translations.strategic_document_id = strategic_document.id and strategic_document_translations.locale = \''.app()->getLocale().'\'
                join field_of_actions on field_of_actions.id = strategic_document.policy_area_id
                join field_of_action_translations on field_of_action_translations.field_of_action_id = field_of_actions.id and field_of_action_translations.locale = \''.app()->getLocale().'\'
                left join (
                    select * from (
                        with recursive sd_child as (
                        (
                            select
                                strategic_document_children.id,
                                strategic_document_children.strategic_document_id,
                                strategic_document_children.parent_id,
                                strategic_document_children.strategic_document_level_id,
                                strategic_document_children.policy_area_id,
                                strategic_document_children_translations.title,
                                0 as depth,
                                array[(strategic_document_children.id || \'\')::varchar] as path
                            from strategic_document_children
                            inner join strategic_document_children_translations on strategic_document_children_translations.strategic_document_children_id = strategic_document_children.id and strategic_document_children_translations.locale = \''.app()->getLocale().'\'
                            where strategic_document_children.parent_id is null and strategic_document_children.deleted_at is null
                        )
                        union all
                        (
                            select
                                strategic_document_children.id,
                                strategic_document_children.strategic_document_id,
                                strategic_document_children.parent_id,
                                strategic_document_children.strategic_document_level_id,
                                strategic_document_children.policy_area_id,
                                strategic_document_children_translations.title,
                                depth + 1 as depth,
                                path || strategic_document_children.id::varchar
                            from strategic_document_children
                            inner join strategic_document_children_translations on strategic_document_children_translations.strategic_document_children_id = strategic_document_children.id and strategic_document_children_translations.locale = \''.app()->getLocale().'\'
                                inner join sd_child on sd_child.id = strategic_document_children.parent_id
                                where strategic_document_children.deleted_at is null
                            )
                        )
                        select * from sd_child
                    ) children
                ) children on children.strategic_document_id = strategic_document.id
                where
                    strategic_document.active = true
                    and strategic_document.deleted_at is null
                    and strategic_document.strategic_document_level_id = '.$cat.'
                group by strategic_document.id, field_of_actions.id, field_of_action_translations.name, children.id, children.depth, children.path
                order by field_of_action_translations.name, strategic_document.id, children.path, children.depth asc
            ');

//            $items[$cat]['items'] = StrategicDocument::select(['strategic_document.*',
//                \DB::raw('case when max(field_of_actions.parentid) = '.InstitutionCategoryLevelEnum::fieldOfActionCategory(InstitutionCategoryLevelEnum::AREA->value). ' then \''.trans_choice('custom.areas', 1).'\' || \' \' || field_of_action_translations.name
//                else (case when  max(field_of_actions.parentid) = '.InstitutionCategoryLevelEnum::fieldOfActionCategory(InstitutionCategoryLevelEnum::MUNICIPAL->value).' then \''.trans_choice('custom.municipalities', 1).'\' || \' \' || field_of_action_translations.name else field_of_action_translations.name end) end as policy')])
//                ->Active()
//                ->with(['translations', 'policyArea', 'policyArea.translations'])
//                ->join('field_of_actions', 'field_of_actions.id', '=', 'strategic_document.policy_area_id')
//                ->join('field_of_action_translations', function ($j){
//                    $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
//                        ->where('field_of_action_translations.locale', '=', app()->getLocale());
//                })->where('strategic_document.strategic_document_level_id', '=', $cat)
//                ->orderBy('field_of_action_translations.name', 'asc')
//                ->GroupBy('strategic_document.id', 'field_of_action_translations.name')
//                ->get();
        }

        if( $request->ajax() ) {
            return view('site.strategic_documents.list_tree', compact( 'items', 'editRouteName', 'deleteRouteName'));
        }

        $pageTitle = trans('custom.strategy_documents_plural');
        $this->composeBreadcrumbs(null, array(['name' => trans_choice('custom.tree_view', 1), 'url' => '']));

        return $this->view('site.strategic_documents.tree', compact('items', 'pageTitle', 'editRouteName', 'deleteRouteName'));
    }

    /**
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show(int $id): View
    {
        $strategicDocument = StrategicDocument::with(['documentType.translations'])->findOrFail($id);
        $locale = app()->getLocale();

        $strategicDocumentFiles = StrategicDocumentFile::with('translations')
            ->where('strategic_document_id', $id)
            ->where('locale', $locale)
            ->whereDoesntHave('documentType.translations', function ($query) {
                $query->where('name', 'like', '%Отчети%')
                    ->orWhere('name', 'like', '%Доклади%');
            })
            ->get();

        $actNumber = $strategicDocument->pris?->doc_num ?? $strategicDocument->strategic_act_number;
        $reportsAndDocs = $strategicDocument->files()->where('locale', $locale)->whereHas('documentType.translations', function($query) {
            $query->where('name', 'like', '%Отчети%')->orWhere('name', 'like', '%Доклади%');
        })->get();
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_STRATEGY_DOC.'_'.app()->getLocale())->first();
        $pageTitle = $strategicDocument->title;
        $this->setBreadcrumbsTitle($pageTitle);

        $this->composeBreadcrumbs($strategicDocument);

        $documents = StrategicDocumentChildren::getTree(0,$strategicDocument->id);

        $hasSubscribeEmail = $this->hasSubscription($strategicDocument);
        $hasSubscribeRss = $this->hasSubscription($strategicDocument, null, null, UserSubscribe::CHANNEL_RSS);
        return $this->view('site.strategic_documents.view', compact('strategicDocument', 'strategicDocumentFiles',
            'actNumber', 'reportsAndDocs', 'pageTitle', 'pageTopContent', 'documents', 'hasSubscribeEmail', 'hasSubscribeRss'));
    }

    public function contacts(Request $request, $itemId = null)
    {
        $pageTitle = $this->pageTitle;
        $moderators = User::role([CustomRole::MODERATOR_STRATEGIC_DOCUMENTS, CustomRole::MODERATOR_STRATEGIC_DOCUMENT])->get();
        $this->composeBreadcrumbs(null, array(['name' => trans_choice('custom.contacts', 2), 'url' => '']));
        return $this->view('site.strategic_documents.contacts', compact('moderators', 'pageTitle'));
    }

    public function documents()
    {
        $page = Page::with(['files' => function($q) {
            $q->where('locale', '=', app()->getLocale());
        }])
            ->where('system_name', '=', Page::STRATEGIC_DOCUMENT_DOCUMENTS)
            ->first();
        if(!$page){
            abort(404);
        }
        $pageTitle = $this->pageTitle;
        $this->setSeo($page->meta_title, $page->meta_description, $page->meta_keyword);
        $this->composeBreadcrumbs(null, array(['name' => $page->name, 'url' => '']));
        return $this->view('site.strategic_documents.page', compact('page', 'pageTitle'));
    }

    public function info()
    {
        $page = Page::with(['files' => function($q) {
            $q->where('locale', '=', app()->getLocale());
        }])
            ->where('system_name', '=', Page::STRATEGIC_DOCUMENT_INFO)
            ->first();
        if(!$page){
            abort(404);
        }
        $pageTitle = $this->pageTitle;
        $this->setSeo($page->meta_title, $page->meta_description, $page->meta_keyword);
        $this->composeBreadcrumbs(null, array(['name' => $page->name, 'url' => '']));
        return $this->view('site.strategic_documents.page', compact('page', 'pageTitle'));
    }

    public function reports(Request $request)
    {
        //Filter
        $rf = $request->all();
        $requestFilter = $request->all();
        if(empty($rf)){
            $requestFilter['status'] = 'active';
        }
        $filter = $this->filtersReport($request, $rf);
        //Sorter
        $sorter = $this->sorters();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'title';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');

        $paginate = $requestFilter['paginate'] ?? Pris::PAGINATE;
        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        $q = StrategicDocument::select('strategic_document.*')
            ->Active()
            ->with(['translations', 'policyArea', 'policyArea.translations'])
            ->leftJoin('field_of_actions', 'field_of_actions.id', '=', 'strategic_document.policy_area_id')
            ->leftJoin('field_of_action_translations', function ($j){
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('strategic_document_translations', function ($j){
                $j->on('strategic_document_translations.strategic_document_id', '=', 'strategic_document.id')
                    ->where('strategic_document_translations.locale', '=', app()->getLocale());
            })
            ->FilterBy($requestFilter)
            ->SortedBy($sort,$sortOrd);
            //->GroupBy('strategic_document.id');

        if($request->input('export_excel') || $request->input('export_pdf')){
            $items = $q->get();
            $exportData = [
                'title' => __('custom.strategic_documents_report_title'),
                'rows' => $items
            ];

            $fileName = 'sd_report_'.Carbon::now()->format('Y_m_d_H_i_s');
            if($request->input('export_pdf')){
                $pdf = PDF::loadView('exports.sd_report', ['data' => $exportData, 'isPdf' => true])->setPaper('a4', 'landscape');
                return $pdf->download($fileName.'.pdf');
            } else{
                return Excel::download(new StrategicDocumentsExport($exportData), $fileName.'.xlsx');
            }
        } else{
            $items = $q->paginate($paginate);
        }

        if( $request->ajax() ) {
            return view('site.strategic_documents.list_report', compact('filter','sorter', 'items', 'rf'));
        }

        $pageTitle = trans('custom.strategy_documents_plural');
        $this->composeBreadcrumbs(null, array(['name' => __('site.strategic_document.all_documents_report'), 'url' => '']));

        return $this->view('site.strategic_documents.report', compact('filter','sorter', 'items', 'pageTitle', 'rf', 'defaultOrderBy', 'defaultDirection'));
    }

    private function filters($request, $currentRequest)
    {
        return array(
            'level' => array(
                'type' => 'select',
                'options' => enumToSelectOptions(InstitutionCategoryLevelEnum::options(), 'strategic_document.dropdown', true, [InstitutionCategoryLevelEnum::CENTRAL_OTHER->value]),
                'multiple' => true,
                'default' => '',
                'label' => __('site.strategic_document.level'),
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
                'label' => trans_choice('custom.municipalities', 2),
                'value' => $request->input('municipalities'),
                'col' => 'col-md-4'
            ),
            'status' => array(
                'type' => 'select',
                'label' => __('site.strategic_document.categories_based_on_livecycle'),
                'multiple' => false,
                'options' => array(
                    ['name' => '', 'value' => ''],
                    ['name' => trans_choice('custom.effective', 1), 'value' => 'active'],
                    ['name' => trans_choice('custom.expired', 1), 'value' => 'expired'],
                    ['name' => trans_choice('custom.in_process_of_consultation', 1), 'value' => 'public_consultation']
                ),
                'value' => request()->input('status'),
                'default' => empty($currentRequest) ? 'active' :'',
                'col' => 'col-md-6'
            ),
            'title' => array(
                'type' => 'text',
                'label' => __('site.strategic_document.search_in_title_content'),
                'value' => $request->input('title'),
                'col' => 'col-md-6'
            ),
            'paginate' => array(
                'type' => 'select',
                'options' => paginationSelect(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.filter_pagination'),
                'value' => $request->input('paginate') ?? Pris::PAGINATE,
                'col' => 'col-md-3'
            ),

        );
    }

    private function filtersReport($request, $currentRequest){
        return array(
            'level' => array(
                'type' => 'select',
                'options' => enumToSelectOptions(InstitutionCategoryLevelEnum::options(), 'strategic_document.dropdown', true, [InstitutionCategoryLevelEnum::CENTRAL_OTHER->value]),
                'multiple' => true,
                'default' => '',
                'label' => __('site.strategic_document.level'),
                'value' => $request->input('level'),
                'col' => 'col-md-6'
            ),
            'acceptActInstitution' => array(
                'type' => 'select',
                'options' => optionsFromModel(AuthorityAcceptingStrategic::optionsList(), false),
                'multiple' => true,
                'default' => '',
                'label' => __('validation.attributes.accept_act_institution_type_id'),
                'value' => $request->input('acceptActInstitution'),
                'col' => 'col-md-6'
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
                'label' => trans_choice('custom.municipalities', 2),
                'value' => $request->input('municipalities'),
                'col' => 'col-md-4'
            ),
            'validFrom' => array(
                'type' => 'datepicker',
                'value' => $request->input('validFrom'),
                'label' => __('custom.valid_from'),
                'col' => 'col-md-3'
            ),
            'validTo' => array(
                'type' => 'datepicker',
                'value' => $request->input('validTo'),
                'label' => __('custom.valid_to'),
                'col' => 'col-md-3'
            ),
            'status' => array(
                'type' => 'select',
                'label' => __('site.strategic_document.categories_based_on_livecycle'),
                'multiple' => false,
                'options' => array(
                    ['name' => '', 'value' => ''],
                    ['name' => trans_choice('custom.effective', 1), 'value' => 'active'],
                    ['name' => trans_choice('custom.expired', 1), 'value' => 'expired'],
                    ['name' => trans_choice('custom.in_process_of_consultation', 1), 'value' => 'public_consultation']
                ),
                'value' => request()->input('status'),
                'default' => empty($currentRequest) ? 'active' :'',
                'col' => 'col-md-6'
            ),
            'paginate' => array(
                'type' => 'select',
                'options' => paginationSelect(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.filter_pagination'),
                'value' => $request->input('paginate') ?? Pris::PAGINATE,
                'col' => 'col-md-3'
            ),

        );
    }

    private function filtersTree($request, $currentRequest)
    {
        return array(
            'level' => array(
                'type' => 'select',
                'options' => enumToSelectOptions(InstitutionCategoryLevelEnum::options(), 'nomenclature_level', true, [InstitutionCategoryLevelEnum::CENTRAL_OTHER->value]),
                'multiple' => true,
                'default' => '',
                'label' => __('site.strategic_document.level'),
                'value' => $request->input('level'),
                'col' => 'col-md-12'
            ),
            'status' => array(
                'type' => 'select',
                'label' => __('site.strategic_document.categories_based_on_livecycle'),
                'multiple' => false,
                'options' => array(
                    ['name' => '', 'value' => ''],
                    ['name' => trans_choice('custom.effective', 1), 'value' => 'active'],
                    ['name' => trans_choice('custom.expired', 1), 'value' => 'expired'],
                    ['name' => trans_choice('custom.in_process_of_consultation', 1), 'value' => 'public_consultation']
                ),
                'value' => request()->input('status'),
                'default' => empty($currentRequest) ? 'active' :'',
                'col' => 'col-md-6'
            ),
            'title' => array(
                'type' => 'text',
                'label' => __('site.strategic_document.search_in_title_content'),
                'value' => $request->input('title'),
                'col' => 'col-md-6'
            )
        );
    }

    private function sorters()
    {
        return array(
            'fieldOfAction' => ['class' => 'col-md-3', 'label' => trans_choice('custom.field_of_actions', 1)],
            'title' => ['class' => 'col-md-2', 'label' => __('custom.title')],
            'validFrom' => ['class' => 'col-md-3', 'label' => __('custom.valid_from')],
            'validTo' => ['class' => 'col-md-3', 'label' => __('custom.valid_to')],
        );
    }

    public function previewModalFile(Request $request, $id = 0)
    {

        $file = StrategicDocumentFile::findOrFail($id);
        if (!$file) {
            return __('messages.record_not_found');
        }

        return fileHtmlContent($file);

    }

    public function downloadDocFile($id)
    {
        try {
            $file = StrategicDocumentFile::findOrFail($id);
            if (Storage::disk('public_uploads')->has($file->path)) {
                return Storage::disk('public_uploads')->download($file->path, $file->filename);
            } else {
                return back()->with('warning', __('messages.record_not_found'));
            }
        } catch (\Throwable $throwable) {
            return "Could not download file";
        }
    }

    /**
     * @param $item
     * @param $extraItems
     * @return void
     */
    private function composeBreadcrumbs($item = null, $extraItems = []){
        $customBreadcrumbs = array(
            ['name' => trans_choice('custom.strategic_documents', 1), 'url' => route('strategy-documents.index')]
        );

        if($item && $item->strategic_document_level_id){
            $customBreadcrumbs[] = ['name' => __('custom.strategic_document.levels.'.InstitutionCategoryLevelEnum::keyByValue($item->strategic_document_level_id)), 'url' => route('strategy-documents.index').'?level[]='.$item->strategic_document_level_id];
        }

        if($item && $item->policyArea){
            $field = 'fieldOfActions';
            if($item && $item->strategic_document_level_id == InstitutionCategoryLevelEnum::MUNICIPAL->value) {
                $field = 'municipalities';
            }
            if($item && $item->strategic_document_level_id == InstitutionCategoryLevelEnum::AREA->value) {
                $field = 'areas';
            }
            $customBreadcrumbs[] = ['name' => $item->policyArea->name, 'url' => route('strategy-documents.index').'?'.$field.'[]='.$item->policyArea->id.'&level[]='.$item->strategic_document_level_id];
        }

        if($item){
             $customBreadcrumbs[] = ['name' => $item->title, 'url' => !empty($extraItems) ? route('advisory-boards.view', $item) : null];
        }

        if(!empty($extraItems)){
            foreach ($extraItems as $eItem){
                $customBreadcrumbs[] = $eItem;
            }
        }
        $this->setBreadcrumbsFull($customBreadcrumbs);
    }
}
