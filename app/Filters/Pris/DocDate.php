<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class DocDate extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->where('pris.doc_date', '>=', Carbon::parse($value)->startOfDay())
                ->where('pris.doc_date', '<=', Carbon::parse($value)->endOfDay());
        }
    }
}
