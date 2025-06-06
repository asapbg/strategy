<?php

namespace App\Filters\PublicConsultation;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class ConsultationNumber extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) ){
            $this->query->where('public_consultation.reg_num', 'ilike', '%'.$value.'%');
        }
    }
}

