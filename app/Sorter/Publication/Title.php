<?php namespace App\Sorter\Publication;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class Title extends QuerySorter implements SorterContract{

    public function handle($value): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('publication_translations.title', $direction);
    }
}


