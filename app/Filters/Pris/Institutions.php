<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Institutions extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        //Need join to work
        if( sizeof($value) ){
            $this->query->whereIn('institution.id', $value);
        }
    }
}

