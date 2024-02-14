<?php

namespace App\Http\Controllers;

use App\Enums\InstitutionCategoryLevelEnum;
use App\Models\AuthorityAcceptingStrategic;
use App\Models\EkatteArea;
use App\Models\EkatteMunicipality;
use App\Models\FieldOfAction;
use App\Models\File;
use App\Models\PolicyArea;
use App\Models\Pris;
use App\Models\Setting;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentChildren;
use App\Models\StrategicDocumentFile;
use App\Models\StrategicDocumentLevel;
use App\Models\StrategicDocuments\Institution;
use App\Services\Exports\ExportService;
use App\Services\FileOcr;
use App\Services\StrategicDocuments\CommonService;
use App\Services\StrategicDocuments\FileService;
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

class StrategicDocumentsController extends Controller
{
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

        $items = StrategicDocument::select('strategic_document.*')
            ->Active()
            ->with(['translations', 'policyArea', 'policyArea.translations'])
            ->join('field_of_actions', 'field_of_actions.id', '=', 'strategic_document.policy_area_id')
            ->join('field_of_action_translations', function ($j){
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->join('strategic_document_translations', function ($j){
                $j->on('strategic_document_translations.strategic_document_id', '=', 'strategic_document.id')
                    ->where('strategic_document_translations.locale', '=', app()->getLocale());
            })
            ->FilterBy($requestFilter)
            ->SortedBy($sort,$sortOrd)
            //->GroupBy('strategic_document.id')
            ->paginate($paginate);


        if( $request->ajax() ) {
            return view('site.strategic_documents.list', compact('filter','sorter', 'items', 'rf', 'editRouteName', 'deleteRouteName'));
        }

        $pageTitle = trans('custom.strategy_documents_plural');
        $this->composeBreadcrumbs(null, array(['name' => trans_choice('custom.table_view', 1), 'url' => '']));

        return $this->view('site.strategic_documents.index', compact('filter','sorter', 'items', 'pageTitle', 'rf', 'defaultOrderBy', 'defaultDirection', 'editRouteName', 'deleteRouteName'));
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
            $items[$cat]['items'] = StrategicDocument::select(['strategic_document.*', 'field_of_action_translations.name as policy'])
                ->Active()
                ->with(['translations', 'policyArea', 'policyArea.translations'])
                ->join('field_of_actions', 'field_of_actions.id', '=', 'strategic_document.policy_area_id')
                ->join('field_of_action_translations', function ($j){
                    $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                        ->where('field_of_action_translations.locale', '=', app()->getLocale());
                })->where('strategic_document.strategic_document_level_id', '=', $cat)
                ->orderBy('field_of_action_translations.name', 'asc')
                ->GroupBy('strategic_document.id', 'field_of_action_translations.name')
                ->get();
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
        return $this->view('site.strategic_documents.view', compact('strategicDocument', 'strategicDocumentFiles', 'actNumber', 'reportsAndDocs', 'pageTitle', 'pageTopContent', 'documents'));
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
            'title' => ['class' => 'col-md-3', 'label' => __('custom.title')],
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

        if($item && $item->documentLevel){
            $customBreadcrumbs[] = ['name' => $item->documentLevel->name, 'url' => route('strategy-documents.index').'?document-level='.$item->documentLevel->id];
        }

        if($item && $item->strategic_document_level_id == InstitutionCategoryLevelEnum::AREA->value && $item->ekatteArea){
            $customBreadcrumbs[] = ['name' => $item->ekatteArea->ime, 'url' => route('strategy-documents.index').'?ekate-area='.$item->ekatteArea->id.'&document-level='.$item->strategic_document_level_id];
        } else if($item && $item->strategic_document_level_id == InstitutionCategoryLevelEnum::MUNICIPAL->value && $item->ekatteManiputlicity){
            $customBreadcrumbs[] = ['name' => $item->ekatteManiputlicity->ime, 'url' => route('strategy-documents.index').'?ekate-municipality='.$item->ekatteManiputlicity->id.'&document-level='.$item->strategic_document_level_id];
        } else if($item && $item->strategic_document_level_id == InstitutionCategoryLevelEnum::CENTRAL->value && $item->policyArea){
            $customBreadcrumbs[] = ['name' => $item->policyArea->name, 'url' => route('strategy-documents.index').'?policy-area='.$item->policyArea->id];
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
