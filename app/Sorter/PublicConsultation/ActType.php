<?php namespace App\Sorter\PublicConsultation;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class ActType extends QuerySorter implements SorterContract{

    public function handle($value, $filter = null): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('act_type_translations.name', $direction);
    }
}


