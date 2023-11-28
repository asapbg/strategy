<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class FromDate extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( !empty($value) ){
            $this->query->where('pris.doc_date', '>=', Carbon::parse($value)->startOfDay());
        }
    }
}

