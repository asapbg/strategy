<?php

namespace App\Filters\AdvisoryBoard;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class FieldOfActions extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( is_array($value) && sizeof($value) ){
            if(str_contains($value[0], ',')) {
                $explode = explode(',', $value[0]);
                $this->query->whereIn('advisory_boards.policy_area_id', $explode);
            } else{
                $this->query->whereIn('advisory_boards.policy_area_id', $value);
            }
        }
    }
}

