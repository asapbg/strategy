<?php

namespace App\Filters\StrategicDocument;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Areas extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( is_array($value) && sizeof($value) ){
            if(str_contains($value[0], ',')) {
                $explode = explode(',', $value[0]);
                $this->query->whereIn('strategic_document.policy_area_id', $explode);
            } else{
                $this->query->whereIn('strategic_document.policy_area_id', $value);
            }
        }
    }
}

