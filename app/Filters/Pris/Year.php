<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class Year extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ) {
            $this->query->where(function ($q) use($value){
                $q->where('pris.doc_date', '>=', $value . '-01-01')->where('pris.doc_date', '<=', $value . '-12-31');
            });
        }
    }
}

