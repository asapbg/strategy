<?php

namespace App\Filters\Tag;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Title extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( !empty($value) ){
            $this->query->whereHas('translations', function ($query) use ($value) {
                $query->where('locale', app()->getLocale());
                $query->where('label', 'ilike', '%'.$value.'%');
            });
        }
    }
}

