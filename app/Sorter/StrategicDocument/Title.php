<?php namespace App\Sorter\StrategicDocument;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class Title extends QuerySorter implements SorterContract{

    public function handle($value, $filter = null): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('strategic_document_translations.title', $direction);
    }
}


