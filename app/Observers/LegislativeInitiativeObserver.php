<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Library\Facebook;
use App\Models\LegislativeInitiative;
use App\Models\Setting;
use App\Models\UserSubscribe;
use Illuminate\Support\Facades\Log;

class LegislativeInitiativeObserver
{

    /**
     * Handle the Legislative initiative "created" event.
     *
     * @param  LegislativeInitiative  $legislativeInitiative
     * @return void
     */
    public function created(LegislativeInitiative  $legislativeInitiative)
    {
        if(!env('DISABLE_OBSERVERS', false)) {
            //TODO post on facebook
            $activeFB = Setting::where('section', '=', Setting::FACEBOOK_SECTION)
                ->where('name', '=', Setting::FACEBOOK_IS_ACTIVE)
                ->get()->first();
            if ($activeFB->value) {
                $facebookApi = new Facebook();
                $facebookApi->postOnPage(array(
                    'message' => 'На Портала за обществени консултации е направено предложение за промяна на '.$legislativeInitiative->law?->name.' и ако събере подкрепа от '. (int)$legislativeInitiative->cap.' регистрирани потребители, ще бъде изпратена автоматично на компетентната институция. Срокът за коментари и подкрепа е: '.displayDate($legislativeInitiative->active_support).'. Вижте повече на линка.',
//                    'message' => 'Публикувана е нова Законодателна инициатива: ' . $legislativeInitiative->facebookTitle,
                    'link' => route('legislative_initiatives.view', $legislativeInitiative),
                    'published' => true
                ));
            }
            //TODO post on twitter


            $this->sendEmails($legislativeInitiative, 'created');
            Log::info('Send subscribe email on creation');
        }
    }

    /**
     * Handle the Legislative initiative "updated" event.
     *
     * @param  LegislativeInitiative  $legislativeInitiative
     * @return void
     */
    public function updated(LegislativeInitiative  $legislativeInitiative)
    {
        if(!env('DISABLE_OBSERVERS', false)) {
            //Check for real changes
            $dirty = $legislativeInitiative->getDirty(); //return all changed fields
            //skip some fields in specific cases
            unset($dirty['updated_at']);

            if (sizeof($dirty)) {
                $this->sendEmails($legislativeInitiative, 'updated');
                Log::info('Send subscribe email on update');
            }
        }

    }

    /**
     * Send emails to all administrators, moderators and subscribed users
     *
     * @param LegislativeInitiative  $legislativeInitiative
     * @param $event
     * @return void
     */
    private function sendEmails(LegislativeInitiative  $legislativeInitiative, $event): void
    {
        $administrators = null;
        $moderators = null;

        if($event == 'updated'){
            //get users by model ID
            $subscribedUsers = UserSubscribe::where('subscribable_type', LegislativeInitiative::class)
                ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                ->where('subscribable_id', '=', $legislativeInitiative->id)
                ->get();
        }else{
            $subscribedUsers = UserSubscribe::where('id', 0)->get();
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
                        if(in_array($legislativeInitiative->id, $modelIds)){
                            $subscribedUsers->add($fSubscribe);
                        }
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
        $data['modelInstance'] = $legislativeInitiative;
        $data['modelName'] = $legislativeInitiative->facebookTitle;
        $data['markdown'] = 'legislative-initiative';

        SendSubscribedUserEmailJob::dispatch($data);
    }

    /**
     * Handle the LegislativeInitiative "deleted" event.
     *
     * @param  LegislativeInitiative  $legislativeInitiative
     * @return void
     */
    public function deleted(LegislativeInitiative  $legislativeInitiative)
    {
        //
    }

    /**
     * Handle the LegislativeInitiative "restored" event.
     *
     * @param  LegislativeInitiative  $legislativeInitiative
     * @return void
     */
    public function restored(LegislativeInitiative  $legislativeInitiative)
    {
        //
    }

    /**
     * Handle the LegislativeInitiative "force deleted" event.
     *
     * @param  LegislativeInitiative  $legislativeInitiative
     * @return void
     */
    public function forceDeleted(LegislativeInitiative  $legislativeInitiative)
    {
        //
    }
}
