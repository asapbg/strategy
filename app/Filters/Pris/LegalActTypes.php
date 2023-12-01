<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class LegalActTypes extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( is_array($value) && sizeof($value) ){
            if(str_contains($value[0], ',')) {
                $explode = explode(',', $value[0]);
                $this->query->whereIn('pris.legal_act_type_id', $explode);
            } else{
                $this->query->whereIn('pris.legal_act_type_id', $value);
            }
        }
    }
}

