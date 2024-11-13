<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Tag extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->whereHas('tags', function ($query) use ($value) {
                return $query->where('tag.id', (int)$value);
            });
        }
    }
}

