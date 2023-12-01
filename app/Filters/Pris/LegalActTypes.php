<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class LegalActTypes extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( sizeof($value) ){
            $this->query->whereIn('pris.legal_act_type_id', $value);
        }
    }
}

