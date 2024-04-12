<?php namespace App\Sorter\Pris;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class DocNum extends QuerySorter implements SorterContract{

    public function handle($value, $filter = null): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('pris.doc_num', $direction);
    }
}


