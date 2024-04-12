<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Changes extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->whereHas('changedDocs', function ($query) use ($value) {
                $query->where('pris.doc_num', 'ilike', '%'.$value.'%');
            });
        }
    }
}

