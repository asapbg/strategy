<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PublicConsultationInstitutionReportExport implements FromView, ShouldAutoSize
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.pc_report_institution', [
            'data' => $this->data,
        ]);
    }

    public function collection()
    {
        return collect($this->data);
    }

}
