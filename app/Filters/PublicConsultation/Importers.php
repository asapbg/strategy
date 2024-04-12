<?php

namespace App\Filters\PublicConsultation;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Importers extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( is_array($value) && sizeof($value) ){
            if(str_contains($value[0], ',')) {
                $explode = explode(',', $value[0]);
                $this->query->whereIn('public_consultation.importer_institution_id', $explode);
            } else{
                $this->query->whereIn('public_consultation.importer_institution_id', $value);
            }
        }
    }
}

