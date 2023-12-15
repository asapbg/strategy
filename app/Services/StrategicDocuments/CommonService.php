<?php

namespace App\Services\StrategicDocuments;

use App\Models\AuthorityAcceptingStrategic;
use App\Models\Consultations\PublicConsultation;
use App\Models\Pris;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentLevel;
use App\Models\User;
use App\Services\Exports\ExportService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\PaginationState;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CommonService
{
    /**
     * @param \Illuminate\Database\Eloquent\Collection $strategicDocuments
     * @return array
     */
    public function prepareExportData(\Illuminate\Database\Eloquent\Collection|LengthAwarePaginator $strategicDocuments): array
    {
        $prepareData = [];
        foreach ($strategicDocuments as $strategicDocument) {
            $prepareData[] = [
                'status' => $strategicDocument->document_status,
                'period_of_time' => Carbon::parse($strategicDocument->document_date_accepted)->format('m-d-Y') . ' - ' . Carbon::parse($strategicDocument->document_date_expiring)->format('m-d-Y'), // check period of time
                'category' => $strategicDocument->documentLevel?->name,
                'strategic_document_type_id' => $strategicDocument->documentType?->name, // check this one which one it is
                'title' => $strategicDocument->document_link,
                'policy_area' => $strategicDocument->policyArea?->name,
                'accept_institution' => $strategicDocument->acceptActInstitution?->name,
                'valid_status' => Carbon::parse($strategicDocument->document_date_expiring),//$strategicDocument->active,
                'count' => $strategicDocuments->count(),
            ];
        }
        return $prepareData;
    }

    public function preparePdfReportData($strategicDocuments)
    {
        $exportService = app(ExportService::class);

        return $exportService->export('', $strategicDocuments, 'report.pdf', 'pdf', 'pdf.report');
    }

    public function prisActSelect2Search(Request $request, ?StrategicDocument $strategicDocument = null)
    {
        $term = $request->input('term');
        $filter = $request->input('filter');
        $prisActs = Pris::with('translations');

        if ($strategicDocument?->publicConsultation) {
            $prisActs = $prisActs->where('public_consultation_id', $strategicDocument?->publicConsultation?->id);
        }
        if (!empty($filter)) {
            $filterParts = explode('=', $filter);
            $key = Arr::get($filterParts, 0);

            $value = Arr::get($filterParts, 1);
            if ($key == 'legal-act-type-id') {
                if ($value != 'all') {
                    $prisActs = Pris::where('legal_act_type_id', $value);
                }
            }
            if ($key == 'public-consultation-id') {
                if ($value != 'all') {
                    $publicConsultation = PublicConsultation::findOrFail($value);
                    if ($publicConsultation) {
                        $prisActs = $prisActs->whereIn('public_consultation_id', [$publicConsultation->id]);
                    }
                }
            }
        }

        if ($term) {
            $prisActs = $prisActs->where('doc_num', 'like', '%' . $term . '%')
                ->orWhere('doc_date', 'like', '%' . $term . '%')->orWhereHas('actType.translations', function($query) use ($term) {
                    $query->where('name', 'ilike', '%' . $term . '%');
                });
        }
        return $prisActs;
    }

    public function parentStrategicDocumentsSelect2Options(Request $request, ?StrategicDocument $item = null)
    {
        //$documentId = $request->get('documentId');
        $term = $request->input('term');

        $strategicDocuments = StrategicDocument::with('translations');
        if ($item) {
            //$item = StrategicDocument::find($documentId);
            //$strategicDocuments = $strategicDocuments->where('policy_area_id', $item->policy_area_id);
        }
        if ($term) {
            $currentLocale = app()->getLocale();
            $strategicDocuments = $strategicDocuments->whereHas('translations', function($query) use ($currentLocale, $term) {
                $query->where('locale', $currentLocale)->where('title', 'ilike', '%' . $term . '%');
            });
        }

        $filter = $this->documentFilter($request);
        $key = Arr::get($filter, 'key');
        $value = Arr::get($filter, 'value');
        if ($key == 'policy-area-id') {
            $strategicDocuments = $strategicDocuments->where('policy_area_id', $value);
        }
        /*
        if (!empty($filter)) {
            $filterParts = explode('=', $filter);
            $key = Arr::get($filterParts, 0);
            $value = Arr::get($filterParts, 1);
            if ($key == 'policy-area-id') {
                $strategicDocuments = $strategicDocuments->where('policy_area_id', $value);
            }
        }
        */
        return $strategicDocuments;
    }

    /**
     * @param Request $request
     * @return string[]
     */
    public function documentFilter(Request $request)
    {
        $filterArray = ['key' => '', 'value' => ''];
        $filter = $request->input('filter');
        if (!empty($filter)) {
            $filterParts = explode('=', $filter);
            $key = Arr::get($filterParts, 0);
            $value = Arr::get($filterParts, 1);
            $filterArray['key'] = $key;
            $filterArray['value'] = $value;
        }

        return $filterArray;
    }

    /**
     * @param StrategicDocument $parentDocument
     * @param $documentOptions
     * @return array
     */
    public function parentStrategicDocumentSelectedOption(StrategicDocument $parentDocument, $documentOptions): array
    {
        $customOption = [
            'id' => $parentDocument->id,
            'text' => $parentDocument->title,
        ];
        $documentOptions['items'] = $documentOptions['items']->toArray();
        array_unshift($documentOptions['items'], $customOption);
        $documentOptions['items'][0]['selected'] = true;

        return $documentOptions;
    }

    /**
     * @return array
     */
    public function mapUserToInstitutions(User $user): array
    {
        $institution = $user->institution;
        $area = null;
        $maniputlicity = null;
        $userNomenclatureLevel = $institution?->level?->nomenclature_level;
        $acceptedStrategicIds = [];

        switch ($userNomenclatureLevel) {
            case 1: // Central level, ministry, and National Assembly
                $acceptedStrategicIds = [1, 2];
                break;
            case 2: // Area level
                $acceptedStrategicIds = [3];
                $area = optional($institution->region);
                break;
            case 3: // Manipulicity level
                $acceptedStrategicIds = [3];
                $userNomenclatureLevel = 2;
                $maniputlicity = $institution->municipality;
                break;
            case 4:
                $userNomenclatureLevel = 3;
                $acceptedStrategicIds = [4];
                $maniputlicity = $institution->municipality;
                break;
        }

        $strategicDocumentLevel = StrategicDocumentLevel::with('translations')->where('id', $userNomenclatureLevel)->get();

        $authorityAcceptingStrategic = AuthorityAcceptingStrategic::with('translations')
            ->whereIn('id', $acceptedStrategicIds)
            ->get();

        return [
            'authority_accepting_strategic' => $authorityAcceptingStrategic,
            'strategic_document_level' => $strategicDocumentLevel,
            'area'  => $area,
            'manipulicity' => $maniputlicity,
        ];
    }
}
