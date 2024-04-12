<?php

namespace App\Filters\StrategicDocument;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;


class Text extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->where(function ($q) use($value){
                $q->where('strategic_document_translations.title', 'ilike', '%'.$value.'%')
                    ->orwhere('strategic_document_translations.description', 'ilike', '%'.$value.'%')
                    ->orWhere(function ($q) use($value){
                        $q->whereHas('files', function (Builder $query) use ($value){
                            $query->whereRaw('sd_file_text_ts_bg @@ plainto_tsquery(\'bulgarian\', ?)', [$value]);
                        });
                    });
            });
        }
    }
}

