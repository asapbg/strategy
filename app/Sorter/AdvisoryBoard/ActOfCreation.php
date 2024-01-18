<?php namespace App\Sorter\AdvisoryBoard;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class ActOfCreation extends QuerySorter implements SorterContract{

    public function handle($value): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('advisory_act_type_translations.name', $direction);
    }
}


