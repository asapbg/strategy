<?php

namespace App\Filters\AdvisoryBoard;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class ActOfCreations extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( is_array($value) && sizeof($value) ){
            if(str_contains($value[0], ',')) {
                $explode = explode(',', $value[0]);
                $this->query->whereIn('advisory_boards.advisory_act_type_id', $explode);
            } else{
                $this->query->whereIn('advisory_boards.advisory_act_type_id', $value);
            }
        }
    }
}

