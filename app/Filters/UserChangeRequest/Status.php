<?php

namespace App\Filters\UserChangeRequest;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Status extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        $value = (int)$value;
        if( $value > 0 ){
            $this->query->where('user_change_request.status', $value);
        }
    }
}

