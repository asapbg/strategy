<?php

namespace App\Filters\Publication;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;

class To extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->where('publication.published_at', '<=', $value);
        }
    }
}

