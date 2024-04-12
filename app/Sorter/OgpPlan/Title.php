<?php namespace App\Sorter\OgpPlan;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class Title extends QuerySorter implements SorterContract{

    public function handle($value, $filter = null): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('ogp_plan_translations.name', $direction);
    }
}


