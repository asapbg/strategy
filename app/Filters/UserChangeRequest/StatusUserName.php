<?php

namespace App\Filters\UserChangeRequest;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class StatusUserName extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->where(function ($q) use($value){
                $q->where('status_user.first_name', 'ilike', '%'.$value.'%')
                    ->orWhere('status_user.middle_name', 'ilike', '%'.$value.'%')
                    ->orWhere('status_user.last_name', 'ilike', '%'.$value.'%');
            });
        }
    }
}

