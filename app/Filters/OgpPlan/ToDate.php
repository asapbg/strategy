<?php

namespace App\Filters\OgpPlan;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class ToDate extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( !empty($value) ){
            $this->query->where('ogp_plan.to_date', '<=' , Carbon::parse($value)->format('Y-m-d'));
        }
    }
}

