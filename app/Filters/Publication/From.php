<?php

namespace App\Filters\Publication;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class From extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( !empty($value) ){
            $this->query->where('event_date', '>=', $value);
        }
    }
}

