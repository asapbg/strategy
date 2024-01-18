<?php

namespace App\Filters\AdvisoryBoard;

use App\Enums\PollStatusEnum;
use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class Status extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( $value > -1 ){
            $this->query->where('advisory_boards.active', $value);
        }
    }
}

