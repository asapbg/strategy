<?php

namespace App\Filters\Tag;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Status extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        $value = (int)$value;
        if( in_array($value, [0,1]) ){
            $this->query->where('tag.active', '=', $value);
        }
    }
}

