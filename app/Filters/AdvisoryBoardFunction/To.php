<?php

namespace App\Filters\AdvisoryBoardFunction;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class To extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( !empty($value) ){
            $this->query->where('working_year', '<=', Carbon::parse($value)->endOfDay());
        }
    }
}

