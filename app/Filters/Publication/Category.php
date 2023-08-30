<?php

namespace App\Filters\Publication;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Category extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( !empty($value) ){
            $this->query->where('publication.publication_category_id', $value);
        }
    }
}

