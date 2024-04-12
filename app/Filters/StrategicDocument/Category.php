<?php

namespace App\Filters\StrategicDocument;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Category extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            if(is_array($value)){
                $this->query->whereIn('strategic_document_level_id', $value);
            } else{
                $this->query->where('strategic_document_level_id', $value);
            }
        }
    }
}

