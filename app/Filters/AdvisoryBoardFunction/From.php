<?php

namespace App\Filters\AdvisoryBoardFunction;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class From extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->where('working_year', '>=', Carbon::parse($value)->startOfDay());
        }
    }
}

