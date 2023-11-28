<?php namespace App\Sorter\Pris;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class Category extends QuerySorter implements SorterContract{

    public function handle($value): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('legal_act_type_translations.name', $direction);
    }
}


