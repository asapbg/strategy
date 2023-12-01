<?php

namespace App\Services\StrategicDocuments;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CommonService
{
    /**
     * @param \Illuminate\Database\Eloquent\Collection $strategicDocuments
     * @return array
     */
    public function prepareExportData(\Illuminate\Database\Eloquent\Collection $strategicDocuments): array
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

}
