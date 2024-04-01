<?php

namespace App\Filters\UserChangeRequest;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class UserName extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( !empty($value) ){
            $this->query->where(function ($q) use($value){
                $q->where('user.first_name', 'ilike', '%'.$value.'%')
                    ->orWhere('user.middle_name', 'ilike', '%'.$value.'%')
                    ->orWhere('user.last_name', 'ilike', '%'.$value.'%');
            });
        }
    }
}

