<?php

namespace App\Filters\AdvisoryBoard;

use App\Enums\PollStatusEnum;
use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class Status extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if(!empty($value) && $value != '-1'){
            if(is_string($value) && $value == 'inactive') {
                $value = 0;
            }
            if(is_string($value) && $value == 'active') {
                $value = 1;
            }

            if( $value > -1 ){
                $this->query->where('advisory_boards.active', (bool)$value);
            }
        }

    }
}

