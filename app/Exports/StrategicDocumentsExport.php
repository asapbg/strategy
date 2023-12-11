<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;

class StrategicDocumentsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    public function __construct(private $data, private $rowCount)
    {
        ini_set('memory_limit', '2048M');

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
            trans('custom.stategic_document_status'),
            trans('custom.stategic_document_active_period_of_time'),
            trans('custom.stategic_document_by_category'), // central level, regional level,
            trans('custom.strategic_document_accepted_by_national_assemly'), // check this one how to map
            trans('custom.strategic_document_tile'), // link to front end page.
            trans('custom.policy_area'), //
            trans_choice('custom.authority_accepting_strategic', 1),
            trans('custom.strategic_document_valid_status'),
            trans('custom.strategic_documents_total_count_in_report'),
        ];
    }

    /**
     * @param $row
     * @return array
     */
    public function map($row): array
    {
        $url = url('strategy-document/', ['id' => $row->id]);
        return [
            $row->document_status,
            Carbon::parse($row->document_date_accepted)->format('m-d-Y') . ' - ' . Carbon::parse($row->document_date_expiring)->format('m-d-Y'),
            $row->documentLevel?->name,
            $row->documentType?->name,
            '=HYPERLINK("' . $url . '","' . $row->title . '")',
            $row->policyArea?->name,
            $row->acceptActInstitution?->name,
            Carbon::parse($row->document_date_expiring),
            $this->rowCount
        ];
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 5000;
    }
}
