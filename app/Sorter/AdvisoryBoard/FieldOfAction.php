<?php namespace App\Sorter\AdvisoryBoard;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class FieldOfAction extends QuerySorter implements SorterContract{

    public function handle($value): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('field_of_action_translations.name', $direction);
    }
}


