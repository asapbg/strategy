<?php

namespace App\Filters\AdvisoryBoard;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class MeetingFrom extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( !empty($value) ){
            $this->query->where('advisory_board_meetings.next_meeting', '>=' , Carbon::parse($value)->format('Y-m-d 00:00:00'));
        }
    }
}

