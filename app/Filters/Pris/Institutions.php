<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Institutions extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( is_array($value) && sizeof($value) ){
            if(str_contains($value[0], ',')) {
                $explode = explode(',', $value[0]);
                $this->query->whereIn('institution.id', $explode);
            } else{
                $this->query->whereIn('institution.id', $value);
            }
        }
    }
}

