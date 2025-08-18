<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PublicConsultationReportExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function query()
    {
        return $this->data['rows'];
    }

    public function headings(): array
    {
        return [
            __('custom.name'),
            trans_choice('custom.field_of_actions', 1),
            __('custom.status'),
            trans_choice('custom.institution', 1),
            trans_choice('custom.act_type', 1),
            'Срок (дни)',
            __('site.public_consultation.short_term_motive_label'),
            __('custom.pc_reports.missing_documents'),
            trans_choice('custom.comment', 2),
            __('custom.pc_reports.standard.comment_report'),
        ];
    }

    public function map($row): array
    {
        $existDocTypes = json_decode($row->doc_types);
        $requiredDocs = \App\Enums\DocTypesEnum::pcRequiredDocTypesByActType($row->act_type_id);
        $doc_type = ' ';

        foreach($requiredDocs as $rd) {
            if (empty($existDocTypes) || !in_array($rd, $existDocTypes)) {
                $doc_type .= (__('custom.public_consultation.doc_type.'.$rd) . ';');
            }
        }

        return [
            $row->title,
            $row->fieldOfAction?->name,
            $row->inPeriod,
            $row->importer_institution_id == env('DEFAULT_INSTITUTION_ID') ? '' : $row->importerInstitution?->name,
            $row->actType?->name,
            $row->daysCnt,
            $row->short_term_reason,
            $doc_type,
            $row->comments->count(),
            $row->proposalReport->count() ? 'Да' : 'Не',
        ];
    }
}
