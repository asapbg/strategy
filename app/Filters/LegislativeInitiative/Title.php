<?php

namespace App\Filters\LegislativeInitiative;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Title extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->whereHas('regulatoryAct.translations', function ($query) use ($value) {
                $query->where('locale', app()->getLocale());
                $query->where('name', 'ilike', '%'.$value.'%');
            });
        }
    }
}

