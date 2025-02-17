<?php

namespace App\Filters\StrategicDocumentType;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Active extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        $value = (int)$value;
        if( in_array($value, [0,1]) ){
            $this->query->where('strategic_document_type.active', '=', $value);
        }
    }
}

