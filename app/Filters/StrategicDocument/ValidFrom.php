<?php

namespace App\Filters\StrategicDocument;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class ValidFrom extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( !empty($value) ){
            $this->query->where('strategic_document.document_date_accepted', '>=' , Carbon::parse($value)->format('Y-m-d 00:00:00'));
        }
    }
}

