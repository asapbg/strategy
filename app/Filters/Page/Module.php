<?php

namespace App\Filters\Page;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Module extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        $value = (int)$value;
        if( $value > 0 ){
            $this->query->where('page.module_enum', '=', $value);
        }
    }
}

