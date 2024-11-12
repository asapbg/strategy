<?php namespace App\Sorter\AdvisoryBoard;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class Authority extends QuerySorter implements SorterContract{

    public function handle($value): void
    {
        $direction = $value ?? 'asc';
//        $this->query->orderBy('authority_advisory_board_translations.name', $direction);
        $this->query->orderByRaw('max(authority_advisory_board_translations.name) '.$direction);

    }
}


