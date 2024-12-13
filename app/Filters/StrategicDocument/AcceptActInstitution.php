<?php

namespace App\Filters\StrategicDocument;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class AcceptActInstitution extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( is_array($value) && sizeof($value) ){
            if (str_contains($value[0], ',')) {
                $explode = explode(',', $value[0]);
                $this->query->whereIn('strategic_document.accept_act_institution_type_id', $explode);
            } else {
                $this->query->whereIn('strategic_document.accept_act_institution_type_id', $value);
            }
        } elseif (!empty($value)){
            $this->query->where('strategic_document.accept_act_institution_type_id', (int)$value);
        }
    }
}

