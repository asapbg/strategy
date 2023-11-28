<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class LegalReason extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( !empty($value) ){
            $this->query->whereHas('translations', function ($query) use ($value) {
                $query->where('locale', app()->getLocale());
                $query->where('legal_reason', 'ilike', '%'.$value.'%');
            });
        }
    }
}

