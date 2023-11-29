<?php

namespace App\Http\Controllers;

use App\Models\AuthorityAcceptingStrategic;
use App\Models\File;
use App\Models\PolicyArea;
use App\Models\Setting;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentFile;
use App\Services\FileOcr;
use App\Services\StrategicDocuments\FileService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Admin\StrategicDocumentsController as AdminStrategicDocumentsController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class StrategicDocumentsController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->title_singular = trans('custom.strategic_documents_title');
    }

    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $paginatedResults = $request->get('pagination-results') ?? 10;
        $strategicDocuments = $this->prepareResults($request)->where('active', 1);
        $policyAreas = PolicyArea::all();
        $preparedInstitutions = AuthorityAcceptingStrategic::all();
        $editRouteName = AdminStrategicDocumentsController::EDIT_ROUTE;
        $deleteRouteName = AdminStrategicDocumentsController::DELETE_ROUTE;
        $categoriesData = $this->prepareCategoriesData($strategicDocuments);
        $strategicDocuments = $strategicDocuments->paginate($paginatedResults);
        $resultCount = $strategicDocuments->total();
        $pageTitle = $this->title_singular;
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_STRATEGY_DOC.'_'.app()->getLocale())->first();
        //return view('templates.strategicheski-dokumenti', compact('strategicDocuments', 'policyAreas', 'preparedInstitutions', 'resultCount', 'editRouteName', 'deleteRouteName'));
        return view('site.strategic_documents.index', compact('strategicDocuments', 'policyAreas', 'preparedInstitutions', 'resultCount', 'editRouteName', 'deleteRouteName', 'categoriesData', 'pageTitle', 'pageTopContent'));
    }

    /**
     * @param Request $request
     * @return Builder
     */
    private function prepareResults(Request $request): Builder
    {
        $strategicDocuments = StrategicDocument::with(['policyArea.translations', 'documentLevel', 'translations'])->active();
        $policyArea = $request->input('policy-area');
        //$preparedInstitutions = $request->input('prepared-institution');
        $categories = $request->input('category');
        $title = $request->input('title');
        $orderBy = $request->input('order_by');
        $direction = $request->input('direction') ?? 'asc';
        $currentLocale = app()->getLocale();

        $dateFrom = $request->input('valid-from');
        $dateTo = $request->input('valid-to');

        $documentDateInfinite = $request->input('date-infinite');
        if ($dateFrom && $dateTo) {
            $dateFrom = Carbon::createFromFormat('d.m.Y', $dateFrom);
            $dateTo = Carbon::createFromFormat('d.m.Y', $dateTo);
            $strategicDocuments->whereBetween('document_date_accepted', [$dateFrom, $dateTo]);
        } elseif ($dateFrom && $documentDateInfinite == 'false') {
            $dateFrom = Carbon::createFromFormat('d.m.Y', $dateFrom);
            $strategicDocuments->where('document_date_accepted', '>=', $dateFrom);
        }

        if ($documentDateInfinite == 'true') {
            $strategicDocuments->whereNull('document_date_expiring');
        }

        if ($title) {
            $strategicDocuments->whereHas('translations', function($query) use ($title, $currentLocale) {
                $query->where('locale', $currentLocale)->where('title', 'like', '%' . $title . '%');
            });
        }
        if ($policyArea) {
            $policyAreaArray = explode(',', $policyArea);
            $strategicDocuments->when(in_array('all', $policyAreaArray), function ($query) {
                return $query;
            }, function ($query) use ($policyAreaArray) {
                return $query->whereIn('policy_area_id', $policyAreaArray);
            });
        }

        if ($categories) {
            $categories = explode(',', $categories);
            $strategicDocuments->when(in_array('all', $categories), function ($query) {
                return $query;
            }, function($query) use ($categories) {
                if (in_array('active', $categories)) {
                    $query->where('document_date_expiring', '>', now())->where('active', 1);
                }
                if (in_array('expired', $categories)) {
                    $query->where('document_date_expiring', '<=', now());
                }
                if (in_array('public_consultation', $categories)) {
                    $query->whereHas('publicConsultation', function ($subquery) {
                        $subquery->where('active', '=', '1')
                            ->where('open_to', '<=', now());
                    });
                }
            });
        }

        $documentLevel = $request->input('document-level');
        if ($documentLevel) {
            $strategicDocuments->when($documentLevel == 'all', function ($query) {
                return $query;
            }, function($query) use ($documentLevel) {
                $query->where('strategic_document_level_id', $documentLevel);
            });
        }

        if ($orderBy == 'policy-area') {
            $strategicDocuments->join('policy_area_translations', 'strategic_document.policy_area_id', '=', 'policy_area_translations.policy_area_id')
                ->where('locale', $currentLocale)
                ->orderBy('policy_area_translations.name', $direction);
        }

        if ($orderBy == 'title') {
            $strategicDocuments->join('strategic_document_translations', 'strategic_document.id', '=', 'strategic_document_translations.strategic_document_id')
                ->where('strategic_document_translations.locale', $currentLocale)
                ->orderBy('strategic_document_translations.title', $direction);
        }

        if ($orderBy == 'valid-from') {
            $strategicDocuments->orderBy('document_date_accepted', $direction);
        }

        if ($orderBy == 'valid-to') {
            $strategicDocuments->orderBy('document_date_expiring', $direction);
        }

        return $strategicDocuments;
    }

    /**
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show(int $id): View
    {
        $strategicDocument = StrategicDocument::with(['documentType.translations'])->findOrFail($id);
        $strategicDocumentFileService = app(FileService::class);
        $locale = app()->getLocale();
        $pageTitle = $this->title_singular;

        $strategicDocumentFiles = StrategicDocumentFile::with('translations')
            ->where('strategic_document_id', $id)
            ->where('locale', $locale)
            ->whereDoesntHave('documentType.translations', function ($query) {
                $query->where('name', 'like', '%Отчети%')
                    ->orWhere('name', 'like', '%Доклади%');
            })
            ->get();
        $mainDocument = $strategicDocumentFiles->where('is_main', 1)->where('locale', $locale)->first();
        $fileData = $strategicDocumentFileService->prepareFileData($strategicDocumentFiles, false);
        $strategicDocumentFiles = $strategicDocumentFileService->prepareFileData($strategicDocumentFiles, false);
        $actNumber = $strategicDocument->pris?->doc_num ?? $strategicDocument->strategic_act_number;
        $reportsAndDocs = $strategicDocument->files()->where('locale', $locale)->whereHas('documentType.translations', function($query) {
            $query->where('name', 'like', '%Отчети%')->orWhere('name', 'like', '%Доклади%');
        })->get();
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_STRATEGY_DOC.'_'.app()->getLocale())->first();
        return $this->view('site.strategic_documents.view', compact('strategicDocument', 'strategicDocumentFiles', 'fileData', 'actNumber', 'mainDocument', 'reportsAndDocs', 'pageTitle', 'pageTopContent'));
    }

    public function previewModalFile(Request $request, $id = 0)
    {
        try {
            $strategicDocumentFile = StrategicDocumentFile::findOrFail($id);
            if (!$strategicDocumentFile) {
                return __('messages.record_not_found');
            }

            return fileHtmlContent($strategicDocumentFile);
        } catch (\Throwable $throwable) {
            return '';
        }
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
     * @return array|array[]
     */
    private function prepareCategoriesData($strategicDocuments): array
    {
        //$categories = StrategicDocument::with('documentLevel.translations')->get();
        $categories = $strategicDocuments->get();
        $categoriesData = ['national' => [], 'regional' => []];

        foreach ($categories as $category) {
            // Централно ниво
            if ($category->documentLevel?->id == 1) {
                $categoriesData['national']['central-level'][] = $category;
            } else {
                if (!$category->documentLevel) {
                    continue;
                }
                // Областно ниво
                if ($category->documentLevel?->id == 2) {
                    $categoriesData['regional']['district-level'][] = $category;
                } else {
                    $categoriesData['regional']['regional-level'][] = $category;
                }
            }
        }

        return $categoriesData;
    }
}
