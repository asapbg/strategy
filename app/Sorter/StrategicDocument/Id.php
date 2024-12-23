<?php

namespace App\Sorter\StrategicDocument;

use App\Sorter\QuerySorter;
use App\Sorter\SorterContract;

class Id extends QuerySorter implements SorterContract{

    public function handle($value): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('strategic_document.id', $direction);
    }

}
