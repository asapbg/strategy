<?php namespace App\Sorter\AdvisoryBoard;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class ChairmanType extends QuerySorter implements SorterContract{

    public function handle($value): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('advisory_chairman_type_translations.name', $direction);
    }
}


