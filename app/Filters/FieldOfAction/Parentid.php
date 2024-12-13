<?php

namespace App\Filters\FieldOfAction;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Parentid extends QueryFilter implements FilterContract
{

    public function handle($value, $filter = null): void
    {
        $value = (int)$value;
        if ($value) {
            $this->query->where('parentid', '=', $value);
        }
    }
}


