<?php

namespace App\Observers;

use App\Enums\PollStatusEnum;
use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\Poll;
use App\Models\UserSubscribe;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        if ($poll->status != PollStatusEnum::INACTIVE->value && Carbon::parse($poll->start_date) <= Carbon::now()->format('Y-m-d')) {
            $this->sendEmails($poll, 'created');
            Log::info('Send subscribe email on creation');
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
        $old_status = (int)$poll->getOriginal('status');
        $start_date = (int)$poll->getOriginal('start_date');

        //Check for real changes
        $dirty = $poll->getDirty(); //return all changed fields
        //skip some fields in specific cases
        unset($dirty['updated_at']);

        if(sizeof($dirty) && ($old_status != $poll->status || $start_date != $poll->start_date) && Carbon::parse($poll->start_date) <= Carbon::now()->format('Y-m-d')){
            $this->sendEmails($poll, 'created');
            Log::info('Send subscribe email on update');
        }
    }

    /**
     * Handle the Pris "deleted" event.
     *
     * @param  \App\Models\Poll  $poll
     * @return void
     */
    public function deleted(Poll $poll)
    {
        //
    }

    /**
     * Handle the Pris "restored" event.
     *
     * @param  \App\Models\Poll  $poll
     * @return void
     */
    public function restored(Poll $poll)
    {
        //
    }

    /**
     * Handle the Pris "force deleted" event.
     *
     * @param  \App\Models\Poll  $poll
     * @return void
     */
    public function forceDeleted(Poll $poll)
    {
        //
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
        if($event == 'created'){
            $administrators = null;
            $moderators = null;
            //get users by model ID
            $subscribedUsers = UserSubscribe::where('subscribable_type', Poll::class)
                ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                ->where('subscribable_id', '=', $poll->id)
                ->get();

            //get users by model filter
            $filterSubscribtions = UserSubscribe::where('subscribable_type', Poll::class)
                ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                ->whereNull('subscribable_id')
                ->get();

            if($filterSubscribtions->count()){
                foreach ($filterSubscribtions as $fSubscribe){
                    $filterArray = is_null($fSubscribe->search_filters) ? [] : json_decode($fSubscribe->search_filters, true);
                    $modelIds = Poll::list($filterArray)->pluck('id')->toArray();
                    if(in_array($poll->id, $modelIds)){
                        $subscribedUsers->add($fSubscribe);
                    }
                }
            }
            if (!$administrators && !$moderators && $subscribedUsers->count() == 0) {
                return;
            }

            $data['event'] = $event;
            $data['administrators'] = $administrators;
            $data['moderators'] = $moderators;
            $data['subscribedUsers'] = $subscribedUsers;
            $data['modelInstance'] = $poll;
            $data['markdown'] = 'poll';

            SendSubscribedUserEmailJob::dispatch($data);

        }
    }
}
