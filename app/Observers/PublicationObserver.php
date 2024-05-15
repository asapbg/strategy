<?php

namespace App\Observers;

use App\Enums\PublicationTypesEnum;
use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\Publication;
use App\Models\User;
use App\Models\UserSubscribe;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PublicationObserver
{
    /**
     * Handle the Publication "created" event.
     *
     * @param  \App\Models\Publication  $publication
     * @return void
     */
    public function created(Publication $publication)
    {
        if(!env('DISABLE_OBSERVERS', false)) {
            if ($publication->active && $publication->published_at <= Carbon::now()->format('Y-m-d')
                && in_array($publication->type, [PublicationTypesEnum::TYPE_NEWS->value, PublicationTypesEnum::TYPE_LIBRARY->value])) {

                $this->sendEmails($publication, 'created');
                Log::info('Send subscribe email on creation');
            }
        }
    }

    /**
     * Handle the Publication "updated" event.
     *
     * @param  \App\Models\Publication  $publication
     * @return void
     */
    public function updated(Publication $publication)
    {
        //
    }

    /**
     * Handle the Publication "deleted" event.
     *
     * @param  \App\Models\Publication  $publication
     * @return void
     */
    public function deleted(Publication $publication)
    {
        //
    }

    /**
     * Handle the Publication "restored" event.
     *
     * @param  \App\Models\Publication  $publication
     * @return void
     */
    public function restored(Publication $publication)
    {
        //
    }

    /**
     * Handle the Publication "force deleted" event.
     *
     * @param  \App\Models\Publication  $publication
     * @return void
     */
    public function forceDeleted(Publication $publication)
    {
        //
    }

    /**
     * Send emails
     *
     * @param Publication $publication
     * @param $event
     * @return void
     */
    private function sendEmails(Publication $publication, $event): void
    {
        $administrators = null;
        $moderators = null;

        $subscribedUsers = UserSubscribe::where('id', 0)->get();

        //get users by model ID
//        $subscribedUsers = UserSubscribe::where('subscribable_type', Publication::class)
//            ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
//            ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
//            ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
//            ->where('subscribable_id', '=', $publication->id)
//            ->get();

        //get users by model filter
        $filterSubscribtions = UserSubscribe::where('subscribable_type', Publication::class)
            ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
            ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
            ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
            ->whereNull('subscribable_id')
            ->get();

        if($filterSubscribtions->count()){
            foreach ($filterSubscribtions as $fSubscribe){
                $filterArray = is_null($fSubscribe->search_filters) ? [] : json_decode($fSubscribe->search_filters, true);
                $modelIds = Publication::list($filterArray, $publication->type)->pluck('id')->toArray();
                if(in_array($publication->id, $modelIds)){
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
        $data['modelInstance'] = $publication;
        $data['markdown'] = 'publication';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}
