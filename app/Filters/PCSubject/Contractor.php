<?php

namespace App\Filters\PCSubject;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Contractor extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( !empty($value) ){
            $this->query->whereHas('translations', function ($query) use ($value) {
                $query->where('locale', app()->getLocale());
                $query->where('contractor', 'ilike', '%'.$value.'%');
            });
        }
    }
}

