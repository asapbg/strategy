<?php

namespace App\Filters\PublicConsultation;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class Status extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) && (int)$value > 0){

            $now = Carbon::now()->format('Y-m-d');
            switch ((int)$value){
                //Активни
                case 1:
                    $this->query->where(function ($q) use($now){
                        $q->where('public_consultation.open_from', '<=', $now);
                        $q->where('public_consultation.open_to', '>=', $now);
                    });
                    break;
                //Неактивни
                case 2:
                    $this->query->where(function ($q) use($now){
                        $q->where('public_consultation.open_from', '>', $now);
                        $q->orWhere('public_consultation.open_to', '<', $now);
                    });
                    break;
            }
        }
    }
}

