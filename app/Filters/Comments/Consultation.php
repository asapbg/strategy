<?php

namespace App\Filters\Comments;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use App\Models\Comments;


class Consultation extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( (int)$value > 0 ){
            $this->query->where('comments.object_code', '=', Comments::PC_OBJ_CODE)
                ->where('comments.object_id', '=', (int)$value);
        }
    }
}

