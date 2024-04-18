<?php

namespace App\Filters\PublicConsultation;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Comments extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) && (int)$value > 0){

            switch ((int)$value){
                //Има
                case 1:
                    $this->query->whereNotNull('comments.id');
                    break;
                //Няма
                case 2:
                    $this->query->whereNull('comments.id');
                    break;
            }
        }
    }
}

