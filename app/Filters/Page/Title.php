<?php

namespace App\Filters\Page;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Title extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->where('page_translations.name', 'ilike', '%'.$value.'%');
        }
    }
}

