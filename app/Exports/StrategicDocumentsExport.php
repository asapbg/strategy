<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StrategicDocumentsExport implements FromCollection, WithHeadings
{

    public function __construct(private $data)
    {
    }
    public function collection()
    {
        return collect($this->data);
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [
            trans('custom.id'),
            trans('custom.title'),
            trans('custom.policy_area'),
            trans('custom.strategic_document_type'),
            trans('custom.accept_act_institution_type'),
            trans('custom.pris'),
            trans('custom.document_date'),
            trans('custom.public_consultation_link'),
            trans('custom.active'),
            trans('custom.consultation_number'),
        ];
    }
}
