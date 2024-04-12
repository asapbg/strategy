<?php

namespace App\Filters\PCSubject;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Type extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->where('type', $value);
        }
    }
}

