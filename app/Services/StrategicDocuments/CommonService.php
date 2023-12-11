<?php

namespace App\Services\StrategicDocuments;

use App\Models\AuthorityAcceptingStrategic;
use App\Models\StrategicDocumentLevel;
use App\Models\User;
use App\Services\Exports\ExportService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\PaginationState;
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
