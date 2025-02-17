<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Category extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( sizeof($value) ){
            $this->query->whereIn('pris.legal_act_type_id', $value);
        }
    }
}

