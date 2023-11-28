<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Institutions extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( sizeof($value) ){
            $this->query->whereIn('institution_id', $value);
        }
    }
}

