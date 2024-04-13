<?php

namespace App\Filters\AdvisoryBoard;

use App\Enums\PollStatusEnum;
use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class Npo extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( $value > -1 ){
            $this->query->where('advisory_boards.has_npo_presence', $value);
        }
    }
}

