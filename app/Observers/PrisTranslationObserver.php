<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Models\Pris;
use App\Models\PrisTranslation;
use App\Models\User;
use App\Models\UserSubscribe;
use Illuminate\Support\Facades\Log;

class PrisTranslationObserver
{
    /**
     * Handle the PrisTranslation "created" event.
     *
     * @param  \App\Models\PrisTranslation  $prisTranslation
     * @return void
     */
    public function created(PrisTranslation $prisTranslation)
    {
        $pris = $prisTranslation->parent;
        if(!env('DISABLE_OBSERVERS', false) && $prisTranslation->locale == config('app.default_lang')) {
            if (!empty($pris->published_at)) {
                $this->sendEmails($prisTranslation, 'created');
                Log::info('Send subscribe email on creation');
                if ($pris->public_consultation_id) {
                    //send if pris pc
                    $this->sendEmails($prisTranslation, 'created_with_pc');
                    Log::info('Send subscribe email on creation');
                }
            }
        }
    }

    /**
     * Handle the PrisTranslation "updated" event.
     *
     * @param  \App\Models\PrisTranslation  $prisTranslation
     * @return void
     */
    public function updated(PrisTranslation $prisTranslation)
    {
        if(!env('DISABLE_OBSERVERS', false)) {
            $old_published_at = $prisTranslation->parent->getOriginal('published_at');
            //Check for real changes
            $dirty = $prisTranslation->getDirty(); //return all changed fields
            //skip some fields in specific cases
            unset($dirty['updated_at']);

            if (sizeof($dirty)) {
//            if (sizeof($dirty) && !empty($pris->published_at)) {
                $event = !$old_published_at && !empty($prisTranslation->parent->published_at) ? 'created' : 'updated';
                $this->sendEmails($prisTranslation, $event);
                Log::info('Send subscribe email on update');
            }
        }
    }

    /**
     * Handle the PrisTranslation "deleted" event.
     *
     * @param  \App\Models\PrisTranslation  $prisTranslation
     * @return void
     */
    public function deleted(PrisTranslation $prisTranslation)
    {
        //
    }

    /**
     * Handle the PrisTranslation "restored" event.
     *
     * @param  \App\Models\PrisTranslation  $prisTranslation
     * @return void
     */
    public function restored(PrisTranslation $prisTranslation)
    {
        //
    }

    /**
     * Handle the PrisTranslation "force deleted" event.
     *
     * @param  \App\Models\PrisTranslation  $prisTranslation
     * @return void
     */
    public function forceDeleted(PrisTranslation $prisTranslation)
    {
        //
    }

    /**
     * Send emails to all administrators, moderators and subscribed users
     *
     * @param PrisTranslation  $prisTranslation
     * @param $event
     * @return void
     */
    private function sendEmails(PrisTranslation  $prisTranslation, $event): void
    {
        Log::error('Observer pris translation event:'.$event);
        $pris = $prisTranslation->parent;
        if($event == 'created' || $event == 'updated'){
            $moderators = null;
            $administrators = null;

            if($event == 'updated'){
                //get users by model ID
                $subscribedUsers = UserSubscribe::where('subscribable_type', Pris::class)
                    ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                    ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                    ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                    ->where('subscribable_id', '=', $pris->id)
                    ->get();
            } else{
                $administrators = User::whereActive(true)
                    ->hasRole(CustomRole::ADMIN_USER_ROLE)
                    ->get();

                $subscribedUsers = UserSubscribe::where('id', 0)->get();
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
            }

            if (!$administrators && !$moderators && $subscribedUsers->count() == 0) {
                return;
            }

            $data['event'] = $event;
            $data['administrators'] = $administrators;
            $data['moderators'] = $moderators;
            $data['subscribedUsers'] = $subscribedUsers;
            $data['modelInstance'] = $pris;
            $data['modelName'] = $pris->mcDisplayName;
            $data['markdown'] = 'pris';


            SendSubscribedUserEmailJob::dispatch($data);

        } else if($event == 'created_with_pc' || $event == 'updated_with_pc'){
            if($pris->public_consultation_id){
                $pc = $pris->consultation;
                $administrators = null;
                $moderators = null;

                if($event == 'updated_with_pc'){
                    //get users by model ID
                    $subscribedUsers = UserSubscribe::where('subscribable_type', PublicConsultation::class)
                        ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                        ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                        ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                        ->where('subscribable_id', '=', $pc->id)
                        ->get();
                } else{
                    $administrators = User::whereActive(true)
                        ->hasRole(CustomRole::ADMIN_USER_ROLE)
                        ->get();
                    $subscribedUsers = UserSubscribe::where('id', 0)->get();
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
                }

                if (!$administrators && !$moderators && $subscribedUsers->count() == 0) {
                    return;
                }

                $data['event'] = $event;
                $data['administrators'] = $administrators;
                $data['moderators'] = $moderators;
                $data['subscribedUsers'] = $subscribedUsers;
                $data['modelInstance'] = $pris->consultation;
                $data['modelName'] = $pris->consultation->title;
                $data['markdown'] = 'public-consultation';


                SendSubscribedUserEmailJob::dispatch($data);
            }
        }
    }
}
