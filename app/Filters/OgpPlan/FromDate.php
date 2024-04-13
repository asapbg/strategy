<?php

namespace App\Filters\OgpPlan;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class FromDate extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->where('ogp_plan.from_date', '>=' , Carbon::parse($value)->format('Y-m-d'));
        }
    }
}

