<?php namespace App\Sorter\AdvisoryBoard;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class Name extends QuerySorter implements SorterContract{

    public function handle($value): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('advisory_board_translations.name', $direction);
    }
}


