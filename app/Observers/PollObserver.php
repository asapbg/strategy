<?php

namespace App\Observers;

use App\Enums\PollStatusEnum;
use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\Poll;
use Carbon\Carbon;

class PollObserver
{
    /**
     * Handle the Poll "created" event.
     *
     * @param  \App\Models\Poll  $poll
     * @return void
     */
    public function created(Poll $poll)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            if ($poll->status != PollStatusEnum::INACTIVE->value && Carbon::parse($poll->start_date) <= Carbon::now()->format('Y-m-d')) {
                $this->sendEmails($poll, 'created');
            }
        }
    }

    /**
     * Handle the Poll "updated" event.
     *
     * @param  \App\Models\Poll  $poll
     * @return void
     */
    public function updated(Poll $poll)
    {
        if(!env('DISABLE_OBSERVERS', false)) {
            $old_status = (int)$poll->getOriginal('status');
            $start_date = (int)$poll->getOriginal('start_date');
            $end_date = (int)$poll->getOriginal('end_date');

            $dirty = $poll->getDirty();
            unset($dirty['updated_at']);

            if (sizeof($dirty)
                && (
                    (!$old_status && $poll->status && Carbon::parse($poll->start_date)->format('Y-m-d') <= Carbon::now()->format('Y-m-d'))
                    ||
                    (
                        $poll->status && ($start_date != $poll->start_date || $end_date != $poll->end_date)
                        && Carbon::parse($start_date)->format('Y-m-d') <= Carbon::now()->format('Y-m-d')
                    )
                )
            ) {
                $this->sendEmails($poll, 'updated');
            }
        }
    }

    /**
     * Send emails
     *
     * @param Poll $poll
     * @param $event
     * @return void
     */
    private function sendEmails(Poll $poll, $event): void
    {
        $data['event'] = $event;
        $data['modelInstance'] = $poll;
        $data['modelName'] = $poll->name;
        $data['markdown'] = 'poll';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}
