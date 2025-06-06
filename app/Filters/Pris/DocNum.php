<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class DocNum extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->where('pris.doc_num', '=', $value);
//            $this->query->where('pris.doc_num', 'ilike', '%'.$value.'%');
        }
    }
}

