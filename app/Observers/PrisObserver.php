<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\Consultations\PublicConsultation;
use App\Models\Pris;
use App\Models\User;
use App\Models\UserSubscribe;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PrisObserver
{
    /**
     * Handle the Pris "created" event.
     *
     * @param  \App\Models\Pris  $pris
     * @return void
     */
    public function created(Pris $pris)
    {
        if (!empty($pris->published_at)) {
            $this->sendEmails($pris, 'created');
            Log::info('Send subscribe email on creation');
            if($pris->public_consultation_id){
                //send if pris pc
                $this->sendEmails($pris, 'created_with_pc');
                Log::info('Send subscribe email on creation');
            }
        }
    }

    /**
     * Handle the Pris "updated" event.
     *
     * @param  \App\Models\Pris  $pris
     * @return void
     */
    public function updated(Pris $pris)
    {
        $old_published_at = (int)$pris->getOriginal('published_at');
        $old_public_consultation_id = (int)$pris->getOriginal('public_consultation_id');

        //Check for real changes
        $dirty = $pris->getDirty(); //return all changed fields
        //skip some fields in specific cases
        unset($dirty['updated_at']);

        if(sizeof($dirty) && !$old_published_at && !empty($pris->published_at)){
            $this->sendEmails($pris, 'created');
            Log::info('Send subscribe email on update');
        }
        if(!$old_public_consultation_id && $pris->public_consultation_id){
            //send if pris pc
            $this->sendEmails($pris, 'created_with_pc');
            Log::info('Send subscribe email on creation');
        }
    }

    /**
     * Handle the Pris "deleted" event.
     *
     * @param  \App\Models\Pris  $pris
     * @return void
     */
    public function deleted(Pris $pris)
    {
        //
    }

    /**
     * Handle the Pris "restored" event.
     *
     * @param  \App\Models\Pris  $pris
     * @return void
     */
    public function restored(Pris $pris)
    {
        //
    }

    /**
     * Handle the Pris "force deleted" event.
     *
     * @param  \App\Models\Pris  $pris
     * @return void
     */
    public function forceDeleted(Pris $pris)
    {
        //
    }

    /**
     * Send emails
     *
     * @param Pris $pris
     * @param $event
     * @return void
     */
    private function sendEmails(Pris $pris, $event): void
    {
        if($event == 'created'){
            $administrators = null;
            $moderators = null;
            //get users by model ID
            $subscribedUsers = UserSubscribe::where('subscribable_type', Pris::class)
                ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                ->where('subscribable_id', '=', $pris->id)
                ->get();

            //get users by model filter
            $filterSubscribtions = UserSubscribe::where('subscribable_type', Pris::class)
                ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                ->whereNull('subscribable_id')
                ->get();

            if($filterSubscribtions->count()){
                foreach ($filterSubscribtions as $fSubscribe){
                    $filterArray = is_null($fSubscribe->search_filters) ? [] : json_decode($fSubscribe->search_filters, true);
                    $modelIds = Pris::list($filterArray)->pluck('id')->toArray();
                    if(in_array($pris->id, $modelIds)){
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
            $data['modelInstance'] = $pris;
            $data['markdown'] = 'pris';

            SendSubscribedUserEmailJob::dispatch($data);

        } else if($event == 'created_with_pc'){
            if($pris->public_consultation_id){
                $pc = $pris->consultation;
                $administrators = null;
                $moderators = null;
                //get users by model ID
                $subscribedUsers = UserSubscribe::where('subscribable_type', PublicConsultation::class)
                    ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                    ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                    ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                    ->where('subscribable_id', '=', $pc->id)
                    ->get();

                //get users by model filter
                $filterSubscribtions = UserSubscribe::where('subscribable_type', PublicConsultation::class)
                    ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                    ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                    ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                    ->whereNull('subscribable_id')
                    ->get();

                if($filterSubscribtions->count()){
                    foreach ($filterSubscribtions as $fSubscribe){
                        $filterArray = is_null($fSubscribe->search_filters) ? [] : json_decode($fSubscribe->search_filters, true);
                        $modelIds = PublicConsultation::list($filterArray, 'title', 'desc', 0)->pluck('id')->toArray();
                        if(in_array($pris->public_consultation_id, $modelIds)){
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
                $data['modelInstance'] = $pris->consultation;
                $data['markdown'] = 'public-consultation';

                SendSubscribedUserEmailJob::dispatch($data);
            }
        }
    }
}