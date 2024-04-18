<?php

namespace App\Filters\PublicConsultation;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class HasShortTermsReasons extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( !empty($value) && (int)$value > 0){

            switch ((int)$value){
                //Има
                case 1:
                    $this->query->whereNotNull('public_consultation_translations.short_term_reason');
                    break;
                //Няма
                case 2:
                    $this->query->whereNull('public_consultation_translations.short_term_reason');
                    break;
            }
        }
    }
}

