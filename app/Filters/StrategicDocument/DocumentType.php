<?php

namespace App\Filters\StrategicDocument;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;

class DocumentType extends QueryFilter implements FilterContract
{
    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            if(is_array($value)){
                $this->query->whereIn('strategic_document.strategic_document_type_id', $value);
            } else{
                $this->query->where('strategic_document.strategic_document_type_id', $value);
            }
        }
    }
}
