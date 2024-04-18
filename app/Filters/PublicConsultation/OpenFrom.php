<?php

namespace App\Filters\PublicConsultation;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class OpenFrom extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->where('public_consultation.open_from', '>=', Carbon::parse($value)->format('Y-m-d'));
        }
    }
}

