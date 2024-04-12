<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Tags extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( sizeof($value) ){
            $this->query->whereHas('tags', function ($query) use ($value) {
                return $query->whereIn('tag.id', $value);
            });
        }
    }
}

