<?php

namespace App\Http\Controllers;

use App\Models\AuthorityAcceptingStrategic;
use App\Models\File;
use App\Models\PolicyArea;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentFile;
use App\Services\FileOcr;
use App\Services\StrategicDocuments\FileService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Admin\StrategicDocumentsController as AdminStrategicDocumentsController;

class StrategicDocumentsController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $paginatedResults = $request->get('paginated-results') ?? 10;
        $strategicDocuments = $this->prepareResults($request)->paginate($paginatedResults);
        $policyAreas = PolicyArea::all();
        $preparedInstitutions = AuthorityAcceptingStrategic::all();
        $resultCount = $strategicDocuments->total();
        $editRouteName = AdminStrategicDocumentsController::EDIT_ROUTE;
        $deleteRouteName = AdminStrategicDocumentsController::DELETE_ROUTE;

        return view('site.strategic_documents.index', compact('strategicDocuments', 'policyAreas', 'preparedInstitutions', 'resultCount', 'editRouteName', 'deleteRouteName'));
    }

    /**
     * @param Request $request
     * @return Builder
     */
    private function prepareResults(Request $request): Builder
    {
        $strategicDocuments = StrategicDocument::with('policyArea');
        $policyArea = $request->input('policy-area');
        $preparedInstitutions = $request->input('prepared-institution');
        $title = $request->input('title');
        if ($title) {
            $currentLocale = app()->getLocale();
            $strategicDocuments->active()->whereHas('translations', function($query) use ($title, $currentLocale) {
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

        if ($preparedInstitutions) {
            $preparedInstitutionsArray = explode(',', $preparedInstitutions);
            $strategicDocuments->when(in_array('all', $preparedInstitutionsArray), function ($query) {
                return $query;
            }, function ($query) use ($preparedInstitutionsArray) {
                return $query->whereIn('accept_act_institution_type_id', $preparedInstitutionsArray);
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
        $strategicDocumentFiles = StrategicDocumentFile::where('strategic_document_id', $id)
            ->whereDoesntHave('documentType.translations', function($query) {
                $query->where('name', 'like', '%Отчети%')->orWhere('name', 'like', '%Доклади%');
            })->where('locale', $locale)->get();
        $fileData = $strategicDocumentFileService->prepareFileData($strategicDocumentFiles, false);
        $actNumber = $strategicDocument->pris?->doc_num ?? $strategicDocument->strategic_act_number;

        $mainDocument = $strategicDocumentFiles->where('is_main', 1)->where('locale', $locale)->first();

        $reportsAndDocs = $strategicDocument->files()->where('locale', $locale)->whereHas('documentType.translations', function($query) {
            $query->where('name', 'like', '%Отчети%')->orWhere('name', 'like', '%Доклади%');
        })->get();

        return $this->view('site.strategic_documents.view', compact('strategicDocument', 'strategicDocumentFiles', 'fileData', 'actNumber', 'mainDocument', 'reportsAndDocs'));
    }
}
