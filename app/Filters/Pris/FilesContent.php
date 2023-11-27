<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;


class FilesContent extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( !empty($value) ){
            $this->query->whereHas('files', function (Builder $query) use ($value){
                $query->whereFullText('file_text', $value);
            });
        }
    }
}

