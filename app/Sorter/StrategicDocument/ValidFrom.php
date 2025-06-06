<?php namespace App\Sorter\StrategicDocument;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class ValidFrom extends QuerySorter implements SorterContract{

    public function handle($value): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderByRaw('strategic_document.document_date_accepted IS NULL, strategic_document.document_date_accepted ' . $direction);
    }
}


