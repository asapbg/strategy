<?php

namespace App\Filters\StrategicDocument;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Title extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->where('strategic_document_translations.title', 'ilike', '%'.$value.'%');
//            $this->query->whereHas('translations', function ($query) use ($value) {
//                $query->where('locale', app()->getLocale());
//                $query->where('title', 'ilike', '%'.$value.'%');
//            });
        }
    }
}

