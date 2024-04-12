<?php namespace App\Sorter\OgpPlan;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class FromDate extends QuerySorter implements SorterContract{

    public function handle($value, $filter = null): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('ogp_plan.from_date', $direction);
    }
}


