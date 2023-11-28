<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class LegalАctТype extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( !empty($value) ){
            $this->query->where('pris.legal_act_type_id', '=', $value);
        }
    }
}

