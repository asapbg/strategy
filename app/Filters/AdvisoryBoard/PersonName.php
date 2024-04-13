<?php

namespace App\Filters\AdvisoryBoard;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;

class PersonName extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->where(function ($q) use ($value){
                $q->where('advisory_board_member_translations.member_name', 'ilike', '%'.$value.'%')
                    ->orwhere('advisory_board_npo_translations.name', 'ilike', '%'.$value.'%');
            });
        }
    }
}
