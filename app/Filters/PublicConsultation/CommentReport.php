<?php

namespace App\Filters\PublicConsultation;

use App\Enums\DocTypesEnum;
use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class CommentReport extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) && (int)$value > 0){

            switch ((int)$value){
                //Има
                case 1:
                    $this->query->whereHas('proposalReport');
                    break;
                //Няма
                case 2:
                    $this->query->whereDoesntHave('proposalReport');
                    break;
            }
        }
    }
}

