<?php

namespace App\Http\Controllers;

use App\Models\AuthorityAcceptingStrategic;
use App\Models\EkatteArea;
use App\Models\EkatteMunicipality;
use App\Models\File;
use App\Models\PolicyArea;
use App\Models\Pris;
use App\Models\Setting;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentFile;
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
        $institutions = Institution::withoutTrashed()->get();
        $strategicDocuments = $this->prepareResults($request);
        $policyAreas = PolicyArea::all();
        $preparedInstitutions = AuthorityAcceptingStrategic::all();
        $editRouteName = AdminStrategicDocumentsController::EDIT_ROUTE;
        $deleteRouteName = AdminStrategicDocumentsController::DELETE_ROUTE;
        $strategicDocumentsCommonService = app(CommonService::class);
        if ($request->input('export')) {
            $exportService = app(ExportService::class);
            if ($request->input('export') == 'pdf') {
                $exportData = $strategicDocumentsCommonService->prepareExportData($strategicDocuments->get());
                return $exportService->export(null, $exportData, 'strategic_documents_export', 'pdf', 'pdf.default');
            }
            if ($request->input('export') == 'xlsx' || $request->input('export') == 'csv') {
                return $exportService->export('App\Exports\StrategicDocumentsExport', $strategicDocuments->get(), 'strategic_documents_export', $request->input('export'));
            }
        }
        if ($request->input('document-report') == 'download') {
            $currentLocale = app()->getLocale();
            $strategicDocs = $strategicDocuments->with(['translations', 'files.translations' => function ($query) use ($currentLocale) {
                $query->where('locale', $currentLocale);
            }])
                ->whereHas('files.translations', function ($query) use ($currentLocale) {
                    $query->where('locale', $currentLocale)->where('visible_in_report', 1);
                })->get();
            return $strategicDocumentsCommonService->preparePdfReportData($strategicDocs);
        }

        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_STRATEGY_DOC.'_'.app()->getLocale())->first();
        $ekateAreas = EkatteArea::all();
        $ekateMunicipalities = EkatteMunicipality::all();
        $prisActs = Pris::all();
        $pageTitle = trans('custom.strategy_documents_plural');
        $title_text = trans('custom.are_you_sure_to_delete');
        $continue_btn_text = trans('custom.delete');
        $cancel_btn_text = trans('custom.cancel');
        $file_change_warning_txt = trans('custom.are_you_sure_to_delete');

        return $this->view('site.strategic_documents.ajax_index', compact('institutions','pageTopContent', 'ekateAreas', 'ekateMunicipalities', 'prisActs', 'pageTitle', 'title_text', 'continue_btn_text', 'cancel_btn_text', 'file_change_warning_txt', 'policyAreas', 'preparedInstitutions', 'editRouteName', 'deleteRouteName'));

        return view('site.strategic_documents.index', compact('strategicDocuments', 'policyAreas', 'preparedInstitutions', 'resultCount', 'editRouteName', 'deleteRouteName', 'categoriesData', 'pageTitle', 'pageTopContent', 'ekateAreas', 'ekateMunicipalities', 'prisActs'));
    }

    public function listStrategicDocuments(Request $request)
    {
        $searchUrl = $request->get('search');
        $parsedUrl = parse_url($searchUrl);
        parse_str(Arr::get($parsedUrl, 'query'), $queryParams);
        $paginatedResults = Arr::get($queryParams, 'pagination-results') ?? $request->get('pagination-results') ?? 10;
        $strategicDocuments = $this->prepareResults($request);
        $editRouteName = AdminStrategicDocumentsController::EDIT_ROUTE;
        $deleteRouteName = AdminStrategicDocumentsController::DELETE_ROUTE;

        if (Arr::get($queryParams, 'view') == 'tree-view') {
            $categoriesData = $this->prepareCategoriesData($strategicDocuments);
            $strategicDocumentsHtml = $this->prepareStrategicDocumentsTreeView($strategicDocuments->get(), $categoriesData);
            $pagination = '';
        } else {
            $strategicDocuments = $strategicDocuments->paginate($paginatedResults);
            $strategicDocumentsHtml = $this->prepareStrategicDocumentsHtml($strategicDocuments, $editRouteName, $deleteRouteName);
            $pagination = $strategicDocuments->links()->toHtml();
        }

        return response()->json(['strategic_documents' => $strategicDocumentsHtml, 'pagination' => $pagination]);
    }

    /**
     * @param string $documentLevelIds
     * @return Application|ResponseFactory|Response
     */
    public function getInstitutions(string $documentLevelIds)
    {
        $documentLevelArray = explode(',', $documentLevelIds);
        if (in_array('all', $documentLevelArray)) {
            return response(['institutions' => Institution::with(['level', 'translations'])->get()]);
        }
        if (in_array(2, $documentLevelArray)) {
            $index = array_search(2, $documentLevelArray);
            $documentLevelArray[$index] = 3;
        }
        if (in_array(3, $documentLevelArray)) {
            $index = array_search(3, $documentLevelArray);
            $documentLevelArray[$index] = 4;
        }
        if (!empty($documentLevelArray)) {
            $institutions = Institution::with(['level', 'translations'])->whereHas('level', function ($query) use ($documentLevelArray) {
                $query->whereIn('nomenclature_level', $documentLevelArray);
            })->get();
        } else {
            $institutions = collect();
        }

        return response(['institutions' => $institutions]);
    }

    private function prepareStrategicDocumentsTreeView($strategicDocuments, $categoriesData)
    {
        $treeViewHtml = '<div class="easy-tree">';
        $treeViewHtml .= '<ul>';
        $treeViewHtml .= '<li class="parent_li">';
        $treeViewHtml .= '<span>';
        $treeViewHtml .= '<span class="glyphicon"></span>';
        $treeViewHtml .= '<a href="#" class="main-color fs-18 fw-600" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="true" aria-controls="multiCollapseExample1">';
        $treeViewHtml .= '<i class="bi bi-pin-map-fill me-1 main-color" title="Национални"></i>';
        $treeViewHtml .= trans_choice('custom.national', 1);
        $treeViewHtml .= '</a>';
        $treeViewHtml .= '</span>';
        $treeViewHtml .= '<ul>';

        foreach ($categoriesData['national'] as $key => $documents) {
            $treeViewHtml .= '<li class="parent_li">';
            $treeViewHtml .= '<span>';
            $treeViewHtml .= '<a href="#" class="main-color fs-18" data-toggle="collapse" data-target="#' . $key . '">';
            $treeViewHtml .= '<i class="fa-solid fa-arrow-right-to-bracket me-1 main-color" title="' . $documents[0]->title . '"></i>';
            $treeViewHtml .= trans_choice('custom.central_level', 1);
            $treeViewHtml .= '</a>';
            $treeViewHtml .= '</span>';
            $treeViewHtml .= '<ul class="collapse show" id="' . $key . '">';

            if (isset($documents)) {
                foreach ($documents as $document) {
                    $treeViewHtml .= '<li class="active-node parent_li">';
                    $treeViewHtml .= '<span>';
                    $treeViewHtml .= '<a href="' . route('strategy-document.view', ['id' => $document->id]) . '">';
                    $treeViewHtml .= $document->title . ' ' . ($document->document_date_accepted ? \Carbon\Carbon::parse($document->document_date_accepted)->format('Y') : '') . ' - ' . ($document->document_date_expiring ? \Carbon\Carbon::parse($document->document_date_expiring)->format('Y') : 'Безсрочен');
                    $treeViewHtml .= '</a>';
                    $treeViewHtml .= '</span>';
                    $treeViewHtml .= '</li>';
                }
            }


            $treeViewHtml .= '</ul>';
            $treeViewHtml .= '</li>';
        }

        $treeViewHtml .= '</ul>';
        $treeViewHtml .= '</li>';

        $treeViewHtml .= '<li class="parent_li">';
        $treeViewHtml .= '<span>';
        $treeViewHtml .= '<span class="glyphicon"></span>';
        $treeViewHtml .= '<a href="#" class="main-color fs-18 fw-600" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="true" aria-controls="multiCollapseExample1">';
        $treeViewHtml .= '<i class="bi bi-pin-map-fill me-1 main-color" title="Национални"></i>';
        $treeViewHtml .= trans_choice('custom.regional', 1);
        $treeViewHtml .= '</a>';
        $treeViewHtml .= '</span>';
        $treeViewHtml .= '<ul>';

        foreach ($categoriesData['regional'] as $key => $documents) {
            $categoryName = $key == 'district-level' ? trans_choice('custom.area_level', 1) : trans_choice('custom.distrinct_level', 1);

            $treeViewHtml .= '<li class="parent_li">';
            $treeViewHtml .= '<span>';
            $treeViewHtml .= '<a href="#" class="main-color fs-18" data-toggle="collapse" data-target="#' . $key . '">';
            $treeViewHtml .= '<i class="fa-solid fa-arrow-right-to-bracket me-1 main-color" title="' . $documents[0]->title . '"></i>';
            $treeViewHtml .= $categoryName;
            $treeViewHtml .= '</a>';
            $treeViewHtml .= '</span>';
            $treeViewHtml .= '<ul class="collapse show" id="' . $key . '">';

            if (isset($documents)) {
                foreach ($documents as $document) {
                    $treeViewHtml .= '<li class="active-node parent_li">';
                    $treeViewHtml .= '<span>';
                    $treeViewHtml .= '<a href="' . route('strategy-document.view', ['id' => $document->id]) . '">';
                    $treeViewHtml .= $document->title . ' ' . ($document->document_date_accepted ? \Carbon\Carbon::parse($document->document_date_accepted)->format('Y') : '') . ' - ' . ($document->document_date_expiring ? \Carbon\Carbon::parse($document->document_date_expiring)->format('Y') : 'Безсрочен');
                    $treeViewHtml .= '</a>';
                    $treeViewHtml .= '</span>';
                    $treeViewHtml .= '</li>';
                }
            }

            $treeViewHtml .= '</ul>';
            $treeViewHtml .= '</li>';
        }

        $treeViewHtml .= '</ul>';
        $treeViewHtml .= '</li>';

        $treeViewHtml .= '</ul>';
        $treeViewHtml .= '</div>';

        return $treeViewHtml;
    }

    private function prepareStrategicDocumentsHtml($strategicDocuments, $editRouteName, $deleteRouteName)
    {
        $strategicDocumentsHtml = '';

        foreach ($strategicDocuments as $document) {
            if (!$document->active) {
                continue;
            }

            $strategicDocumentsHtml .= '<div class="row mb-4">';
            $strategicDocumentsHtml .= '<div class="col-md-12">';
            $strategicDocumentsHtml .= '<div class="consul-wrapper">';
            $strategicDocumentsHtml .= '<div class="single-consultation d-flex">';
            $strategicDocumentsHtml .= '<div class="consult-img-holder">';
            $strategicDocumentsHtml .= '<i class="fa-solid fa-circle-nodes dark-blue"></i>';
            $strategicDocumentsHtml .= '</div>';
            $strategicDocumentsHtml .= '<div class="consult-body">';
            $strategicDocumentsHtml .= '<div class="consul-item">';
            $strategicDocumentsHtml .= '<div class="consult-item-header d-flex justify-content-between">';
            $strategicDocumentsHtml .= '<div class="consult-item-header-link">';
            $strategicDocumentsHtml .= '<a href="' . route('strategy-document.view', ['id' => $document->id]) . '" class="text-decoration-none" title="' . $document->title . '">';
            $strategicDocumentsHtml .= '<h3>' . $document->title . '</h3>';
            $strategicDocumentsHtml .= '</a>';
            $strategicDocumentsHtml .= '</div>';

            $strategicDocumentsHtml .= '<div class="consult-item-header-edit">';


            if (Gate::allows('delete', $document)) {
                $strategicDocumentsHtml .= '<a href="#" class="open-delete-modal">';
                $strategicDocumentsHtml .= '<i class="fas fa-regular fa-trash-can float-end text-danger fs-4 ms-2" role="button"></i>';
                $strategicDocumentsHtml .= '</a>';
                $strategicDocumentsHtml .= '<form class="d-none" method="GET" action="' . route($deleteRouteName, [$document->id]) . '" name="DELETE_ITEM_' . $document->id . '">';
                $strategicDocumentsHtml .= method_field('GET');
                $strategicDocumentsHtml .= csrf_field();
                $strategicDocumentsHtml .= '</form>';
            }
            if (Auth::user()?->can('update', $document)) {
                $strategicDocumentsHtml .= '<a href="' . route($editRouteName, [$document->id]) . '">';
                $strategicDocumentsHtml .= '<i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция"></i>';
                $strategicDocumentsHtml .= '</a>';
            }
            $strategicDocumentsHtml .= '</div>';

            $strategicDocumentsHtml .= '</div>';
            $strategicDocumentsHtml .= $document->category;
            $strategicDocumentsHtml .= '<a class="text-decoration-none mb-3" href="' . route('strategy-document.view', [$document->id]) . '">';
            $strategicDocumentsHtml .= '<i class="bi bi-mortarboard-fill me-1" role="button" title="Образование">';
            $strategicDocumentsHtml .= $document->policyArea->name;
            $strategicDocumentsHtml .= '</i>';
            $strategicDocumentsHtml .= '</a>';

            $strategicDocumentsHtml .= '<div class="meta-consul mt-2">';
            $strategicDocumentsHtml .= '<span class="text-secondary">' .
                ($document->document_date_accepted ? \Carbon\Carbon::parse($document->document_date_accepted)->format('d-m-Y') : '') .
                ' - ' .
                ($document->document_date_expiring ? \Carbon\Carbon::parse($document->document_date_expiring)->format('d-m-Y') : 'Безсрочен') .
                '</span>';
            $strategicDocumentsHtml .= '<a href="' . route('strategy-document.view', ['id' => $document->id]) . '" title="' . $document->title . '">'
                . '<i class="fas fa-arrow-right read-more"></i>'
                . '</a>';
            $strategicDocumentsHtml .= '</div>';
            $strategicDocumentsHtml .= '</div>';
            $strategicDocumentsHtml .= '</div>';
            $strategicDocumentsHtml .= '</div>';
            $strategicDocumentsHtml .= '</div>';
            $strategicDocumentsHtml .= '</div>';
            $strategicDocumentsHtml .= '</div>';
        }

        return $strategicDocumentsHtml;
    }

    /**
     * @param Request $request
     * @return Builder
     */
    private function prepareResults(Request $request): Builder
    {
        $searchUrl = $request->get('search');

        $parsedUrl = parse_url($searchUrl);
        parse_str(Arr::get($parsedUrl, 'query'), $queryParams);
        $strategicDocuments = StrategicDocument::with(['policyArea.translations', 'documentLevel', 'translations', 'acceptActInstitution'])->where('active', 1);
        $policyArea = Arr::get($queryParams, 'policy-area') ?? $request->input('policy-area');
        $categories = Arr::get($queryParams, 'category') ?? $request->input('category');
        $categoriesLifeCycleSelect = Arr::get($queryParams, 'category-lifecycle') ?? $request->input('category-lifecycle');
        $title = Arr::get($queryParams, 'title') ?? $request->input('title');
        $orderBy = Arr::get($queryParams, 'order_by') ?? $request->input('order_by');
        $direction = Arr::get($queryParams, 'direction') ?? $request->input('direction') ?? 'asc';
        $currentLocale = app()->getLocale();
        $documentType = Arr::get($queryParams, 'document-type') ?? $request->input('document-type');
        $ekateArea = Arr::get($queryParams, 'ekate-area') ?? $request->input('ekate-area');
        $ekateMunicipality = Arr::get($queryParams, 'ekate-municipality') ?? $request->input('ekate-municipality');
        $prisActs = Arr::get($queryParams, 'pris-acts') ?? $request->input('pris-acts');
        $preparedInstitutions = Arr::get($queryParams, 'prepared-institution') ?? $request->input('prepared-institution');

        if ($title) {
            $strategicDocuments->where(function ($query) use ($title, $currentLocale) {
                $query->whereHas('translations', function($subQuery) use ($title, $currentLocale) {
                    $subQuery->where('locale', $currentLocale)->where('title', 'ilike', '%' . $title . '%');
                })
                ->orWhereHas('files', function($subQuery) use ($currentLocale, $title) {
                    $subQuery->where('locale', $currentLocale)->where('file_text', 'ilike', '%' . $title . '%');
                });
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

        if ($categories || $categoriesLifeCycleSelect) {
            if ($categories) {
                $categories = explode(',', $categories);
            }
            if ($categoriesLifeCycleSelect) {
                $categoriesLifeCycleSelect = explode(',', $categoriesLifeCycleSelect);
                $categories = array_merge($categories ?? [], $categoriesLifeCycleSelect);
            }

            $strategicDocuments->when(in_array('all', $categories), function ($query) {
                return $query;
            }, function($query) use ($categories) {
                $query->where(function ($subquery) use ($categories) {
                    if (in_array('active', $categories) && in_array('expired', $categories)) {
                        $subquery->where(function ($innerSubquery) {
                            $innerSubquery->where('document_date_expiring', '>', now())
                                ->orWhereNull('document_date_expiring');
                        })->orWhere('document_date_expiring', '<=', now());
                    } elseif (in_array('active', $categories)) {
                        $subquery->where(function ($innerSubquery) {
                            $innerSubquery->where('document_date_expiring', '>', now())
                                ->orWhereNull('document_date_expiring');
                        });
                    } elseif (in_array('expired', $categories)) {
                        $subquery->where('document_date_expiring', '<=', now());
                    }
                    if (in_array('public_consultation', $categories)) {
                        $subquery->orWhereHas('publicConsultation', function ($innerSubquery) {
                            $innerSubquery->where('active', '=', '1')
                                ->where('open_to', '<=', now());
                        });
                    }
                });
            });
        }

        $documentLevel = Arr::get($queryParams, 'document-level') ?? $request->input('document-level');
        if ($documentLevel) {
            $documentLevelArray = explode(',', $documentLevel);
            $strategicDocuments->when(in_array('all', $documentLevelArray), function ($query) {
                return $query;
            }, function($query) use ($documentLevelArray) {
                $query->whereIn('strategic_document_level_id', $documentLevelArray);
            });
        }

        $documentDateFrom = Arr::get($queryParams, 'valid-from') ?? $request->input('valid-from');
        $documentDateTo = Arr::get($queryParams, 'valid-to') ?? $request->input('valid-to');
        $documentDateInfinite = Arr::get($queryParams, 'date-infinite') ?? $request->input('date-infinite');
        $strategicDocuments->when($documentDateFrom, function ($query) use ($documentDateFrom, $documentDateInfinite) {

            $documentDateFrom = Carbon::createFromFormat('d.m.Y', $documentDateFrom);
            return $query->where(function ($subquery) use ($documentDateFrom, $documentDateInfinite) {
                $subquery->where('document_date_accepted', '>=', $documentDateFrom);

                if ($documentDateInfinite == 'true') {
                    $subquery->orWhereNull('document_date_expiring');
                }
            });
        });
        $strategicDocuments->when($documentDateFrom && $documentDateTo && $documentDateInfinite == 'false', function ($query) use ($documentDateFrom, $documentDateTo) {
            $documentDateFrom = Carbon::createFromFormat('d.m.Y', $documentDateFrom);
            $documentDateTo = Carbon::createFromFormat('d.m.Y', $documentDateTo);

            return $query->whereBetween('document_date_accepted', [$documentDateFrom, $documentDateTo]);
        });

        $strategicDocuments->when(!$documentDateFrom && $documentDateTo && $documentDateInfinite == 'false', function ($query) use ($documentDateTo) {
            $documentDateTo = Carbon::createFromFormat('d.m.Y', $documentDateTo);

            return $query->where('document_date_accepted', '<', $documentDateTo);
        });

        if ($orderBy == 'policy-area') {
            $strategicDocuments
                ->select('strategic_document.*')
                ->addSelect([
                    'policy_area_name' => function ($query) use ($currentLocale) {
                        $query->select('name')
                            ->from('policy_area_translations')
                            ->whereColumn('policy_area_translations.policy_area_id', 'strategic_document.policy_area_id')
                            ->where('locale', $currentLocale)
                            ->limit(1);
                    },
                    'title' => function ($query) use ($currentLocale) {
                        $query->select('title')
                            ->from('strategic_document_translations')
                            ->whereColumn('strategic_document_translations.strategic_document_id', 'strategic_document.id')
                            ->where('locale', $currentLocale)
                            ->limit(1);
                    },
                ])
                ->orderBy('policy_area_name', $direction)
                ->orderBy('title', $direction);
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

        if ($ekateArea) {
            $strategicDocuments->where(function($query) use ($ekateArea) {
                $ekateAreaArray = explode(',', $ekateArea);
                $query->when(in_array('all', $ekateAreaArray), function($query) {
                    return $query;
                }, function($query)  use ($ekateAreaArray) {
                    $query->whereIn('ekatte_area_id', $ekateAreaArray);
                });
            });
        }

        if ($ekateMunicipality) {
            $ekateMunicipalityArray = explode(',', $ekateMunicipality);
            $strategicDocuments->where(function($query) use ($ekateMunicipalityArray) {
                $query->when(in_array('all', $ekateMunicipalityArray), function($query) {
                    return $query;
                }, function($query)  use ($ekateMunicipalityArray) {
                    $query->whereIn('ekatte_municipality_id', $ekateMunicipalityArray);
                });
            });
        }

        if ($prisActs) {
            $prisActsArray = explode(',', $prisActs);
            $strategicDocuments->whereIn('pris_act_id', $prisActsArray);
        }

        if ($preparedInstitutions) {
            $preparedInstitutionsArray = explode(',', $preparedInstitutions);
            $strategicDocuments->where(function($query) use ($preparedInstitutionsArray) {
                $query->when(in_array('all', $preparedInstitutionsArray), function($query) {
                    return $query;
                }, function($query) use ($preparedInstitutionsArray) {
                    $query->whereHas('user', function ($userQuery) use ($preparedInstitutionsArray) {
                        $userQuery->whereIn('institution_id', $preparedInstitutionsArray);
                    });
                });
            });
        }
        if ($documentType != 'null') {
            $strategicDocuments->where(function($query) use ($documentType) {
                $query->where('strategic_document_type_id', $documentType);
            });
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
        $pageTitle = $strategicDocument->title;
        $this->setBreadcrumbsTitle($pageTitle);

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
