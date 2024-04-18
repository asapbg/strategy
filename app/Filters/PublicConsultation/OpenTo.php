<?php

namespace App\Filters\PublicConsultation;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class OpenTo extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->where('public_consultation.open_to', '<=', Carbon::parse($value)->format('Y-m-d'));
        }
    }
}

