<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;


class FullSearch extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( !empty($value) ){
            $this->query->where(function ($q) use($value){
                $q->where('pris_translations.about', 'ilike', '%'.$value.'%')
                    ->orWhere('pris_translations.legal_reason', 'ilike', '%'.$value.'%')
                    ->orWhere('tag_translations.label', 'ilike', '%'.$value.'%')
                    ->orWhereHas('files', function (Builder $query) use ($value){
                        $query->whereRaw('file_text_ts_bg @@ plainto_tsquery(\'bulgarian\', ?)', [$value]);
                    });
            });
        };
    }
}


