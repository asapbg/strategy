<?php

namespace App\Filters\Page;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class InFooter extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        $value = (int)$value;
        if( $value > 0 ){
            $this->query->where('page.in_footer', '=', $value);
        }
    }
}

