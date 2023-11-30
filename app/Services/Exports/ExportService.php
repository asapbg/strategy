<?php

namespace App\Services\Exports;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportService
{
    /**
     * @param $exportClass
     * @param $data
     * @param $fileName
     * @param $format
     * @param string|null $pdfView
     * @return Response|BinaryFileResponse|null
     */
    public function export($exportClass, $data, $fileName, $format = 'xlsx', ?string $pdfView = null)
    {
        $export = new $exportClass($data);
        $format = 'xlsx';
        switch ($format) {
            case 'xlsx':
                return Excel::download($export, $fileName . '.xlsx');
            case 'csv':
                return Excel::download($export, $fileName . '.csv');
                break;
            case 'pdf':
                $pdf = \PDF::loadView('pdf.default', compact('data'));
                return $pdf->download($fileName . '.pdf');
        }

        return null;
    }
}
