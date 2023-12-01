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
    public function export($exportClass = null, $data, $fileName, $format = 'xlsx', ?string $pdfView = null)
    {
        $export = $exportClass ? new $exportClass($data, $data->count()) : null;

        switch ($format) {
            case 'xlsx':
                if ($export === null) {
                    throw new \InvalidArgumentException('Export class not provided.');
                }
                return Excel::download($export, $fileName . '.xlsx');
            case 'csv':
                if ($export === null) {
                    throw new \InvalidArgumentException('Export class not provided.');
                }
                return Excel::download($export, $fileName . '.csv');
            case 'pdf':
                if ($pdfView === null) {
                    throw new \InvalidArgumentException('Pdf view not provided.');
                }
                $pdf = \PDF::loadView('pdf.default', compact('data'));
                return $pdf->download($fileName . '.pdf');
            default:
                throw new \InvalidArgumentException('Unsupported export format: ' . $format);
        }
    }
}
