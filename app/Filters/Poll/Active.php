<?php

namespace App\Filters\Poll;

use App\Enums\PollStatusEnum;
use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Carbon\Carbon;


class Active extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( !empty($value) && (int)$value > 0){
            $now = databaseDate(Carbon::now());
            if($value == PollStatusEnum::ACTIVE->value) {
                $this->query->where('poll.start_date', '<=', $now)
                    ->where('poll.end_date', '>=', $now);
            } elseif ($value == PollStatusEnum::EXPIRED->value){
                $this->query->where('poll.end_date', '<=', $now);
            }

        }
    }
}

