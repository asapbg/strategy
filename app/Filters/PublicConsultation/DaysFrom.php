<?php

namespace App\Filters\PublicConsultation;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class DaysFrom extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) && (int)$value > 0){
            $this->query->where('public_consultation.active_in_days', '>=', (int)$value);
        }
    }
}

