<?php

namespace App\Filters\StrategicDocument;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class ValidTo extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->where('strategic_document.document_date_expiring', '<=' , Carbon::parse($value)->format('Y-m-d 00:00:00'));
        }
    }
}

