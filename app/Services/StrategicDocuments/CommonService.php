<?php

namespace App\Services\StrategicDocuments;

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
                'id' => $strategicDocument->id,
                'title' => $strategicDocument->title,
                'policy_area' => $strategicDocument->policyArea->name,
                'strategic_document_type_id' => $strategicDocument->documentType?->name,
                'accept_act_institution_type_id' => $strategicDocument->acceptActInstitution?->name,
                'pris_name' => $strategicDocument->pris?->regNum,
                'document_date' => $strategicDocument->document_date,
                'public_consultation' => $strategicDocument->publicConsultation?->title,
                'active' => trans('custom.active'),//$strategicDocument->active,
                'consultation_number' => $strategicDocument->publicConsultation?->reg_num,
            ];
        }

        return $prepareData;
    }

}
