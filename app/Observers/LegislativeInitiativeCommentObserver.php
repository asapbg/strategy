<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\LegislativeInitiative;
use App\Models\LegislativeInitiativeComment;
use App\Models\UserSubscribe;
use Illuminate\Support\Facades\Log;

class LegislativeInitiativeCommentObserver
{
    /**
     * Handle the LegislativeInitiativeComment "created" event.
     *
     * @param  \App\Models\LegislativeInitiativeComment  $legislativeInitiativeComment
     * @return void
     */
    public function created(LegislativeInitiativeComment $legislativeInitiativeComment)
    {
        $this->sendEmails($legislativeInitiativeComment, 'comment');
        Log::info('Send subscribe email on comment to subscribed and author');

    }

    /**
     * Handle the LegislativeInitiativeComment "updated" event.
     *
     * @param  \App\Models\LegislativeInitiativeComment  $legislativeInitiativeComment
     * @return void
     */
    public function updated(LegislativeInitiativeComment $legislativeInitiativeComment)
    {
        //
    }

    /**
     * Handle the LegislativeInitiativeComment "deleted" event.
     *
     * @param  \App\Models\LegislativeInitiativeComment  $legislativeInitiativeComment
     * @return void
     */
    public function deleted(LegislativeInitiativeComment $legislativeInitiativeComment)
    {
        //
    }

    /**
     * Handle the LegislativeInitiativeComment "restored" event.
     *
     * @param  \App\Models\LegislativeInitiativeComment  $legislativeInitiativeComment
     * @return void
     */
    public function restored(LegislativeInitiativeComment $legislativeInitiativeComment)
    {
        //
    }

    /**
     * Handle the LegislativeInitiativeComment "force deleted" event.
     *
     * @param  \App\Models\LegislativeInitiativeComment  $legislativeInitiativeComment
     * @return void
     */
    public function forceDeleted(LegislativeInitiativeComment $legislativeInitiativeComment)
    {
        //
    }

    /**
     * Send emails to all subscribers and author
     *
     * @param LegislativeInitiativeComment  $legislativeInitiativeComment
     * @param $event
     * @return void
     */
    private function sendEmails(LegislativeInitiativeComment  $legislativeInitiativeComment, $event): void
    {
        $item = LegislativeInitiative::find($legislativeInitiativeComment->legislative_initiative_id);
        $administrators = $moderators = null;
        //get users by model ID
        $subscribedUsers = UserSubscribe::where('subscribable_type', LegislativeInitiative::class)
            ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
            ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
            ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
            ->where('subscribable_id', '=', $item->id)
            ->get();

        //Send to author if comment is not his
        if($item->user && $item->author_id != $legislativeInitiativeComment->user_id){
            $subscribedUsers->add($item->user);
        }

        //get users by model filter
        $filterSubscribtions = UserSubscribe::where('subscribable_type', LegislativeInitiative::class)
            ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
            ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
            ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
            ->whereNull('subscribable_id')
            ->get();

        if($filterSubscribtions->count()){
            foreach ($filterSubscribtions as $fSubscribe){
                $filterArray = json_decode($fSubscribe->search_filters, true);
                if($filterArray){
                    $modelIds = LegislativeInitiative::list($filterArray)->pluck('id')->toArray();
                    if(in_array($item->id, $modelIds)){
                        $subscribedUsers->add($fSubscribe);
                    }
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
        $data['modelInstance'] = $item;
        $data['markdown'] = 'legislative-initiative';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}
