<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class Year extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ) {
            $year = preg_replace('/[^0-9]/', '', $value);
            if(!empty($year)){
                $this->query->where(function ($q) use($year){
                    $q->where('pris.doc_date', '>=', $year . '-01-01')->where('pris.doc_date', '<=', $year . '-12-31');
                });
            }
        }
    }
}

