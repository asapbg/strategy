<?php namespace App\Sorter\StrategicDocument;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class CreatedAt extends QuerySorter implements SorterContract{

    public function handle($value): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('strategic_document.created_at', $direction);
    }
}


