<?php

namespace App\Filters\PublicConsultation;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Days extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) && (int)$value > 0){

            switch ((int)$value){
                //от 14 до 30
                case 1:
                    $this->query->where(function ($q){
                        $q->where('public_consultation.active_in_days', '>=', 14)
                            ->where('public_consultation.active_in_days', '<=', 30);
                    });
                    break;
                //над 30
                case 2:
                    $this->query->where('public_consultation.active_in_days', '>', 30);
                    break;
            }
        }
    }
}

