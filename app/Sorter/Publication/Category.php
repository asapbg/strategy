<?php namespace App\Sorter\Publication;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class Category extends QuerySorter implements SorterContract{

    public function handle($value): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('field_of_action_translations.name', $direction);
    }
}


