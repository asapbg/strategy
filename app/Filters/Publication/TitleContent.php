<?php

namespace App\Filters\Publication;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class TitleContent extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->whereHas('translations', function ($query) use ($value) {
                $query->where('locale', app()->getLocale())->where(function ($q) use ($value){
                    $q->where('title', 'ilike', '%'.$value.'%')->orWhere('content', 'ilike', '%'.$value.'%');
                });
            });
        }
    }
}

