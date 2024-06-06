<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\Consultations\LegislativeProgram;
use App\Models\UserSubscribe;
use Illuminate\Support\Facades\Log;

class LegislativeProgramObserver
{
    /**
     * Handle the LegislativeProgram "created" event.
     *
     * @param  \App\Models\Consultations\LegislativeProgram  $legislativeProgram
     * @return void
     */
    public function created(LegislativeProgram $legislativeProgram)
    {
        if(!env('DISABLE_OBSERVERS', false)) {
            if ($legislativeProgram->public) {
                $this->sendEmails($legislativeProgram, 'created');
                Log::info('Send subscribe email on creation');
            }
        }
    }

    /**
     * Handle the LegislativeProgram "updated" event.
     *
     * @param  \App\Models\Consultations\LegislativeProgram  $legislativeProgram
     * @return void
     */
    public function updated(LegislativeProgram $legislativeProgram)
    {
        if(!env('DISABLE_OBSERVERS', false)) {
            $old_public = (int)$legislativeProgram->getOriginal('public');
            //Check for real changes
            $dirty = $legislativeProgram->getDirty(); //return all changed fields
            //skip some fields in specific cases
            unset($dirty['updated_at']);

            if (sizeof($dirty) && !$old_public && $legislativeProgram->public) {
                $this->sendEmails($legislativeProgram, 'created');
                Log::info('Send subscribe email on update');
            }
        }
    }

    /**
     * Handle the LegislativeProgram "deleted" event.
     *
     * @param  \App\Models\Consultations\LegislativeProgram  $legislativeProgram
     * @return void
     */
    public function deleted(LegislativeProgram $legislativeProgram)
    {
        //
    }

    /**
     * Handle the LegislativeProgram "restored" event.
     *
     * @param  \App\Models\Consultations\LegislativeProgram  $legislativeProgram
     * @return void
     */
    public function restored(LegislativeProgram $legislativeProgram)
    {
        //
    }

    /**
     * Handle the LegislativeProgram "force deleted" event.
     *
     * @param  \App\Models\Consultations\LegislativeProgram  $legislativeProgram
     * @return void
     */
    public function forceDeleted(LegislativeProgram $legislativeProgram)
    {
        //
    }

    /**
     * Send emails
     *
     * @param LegislativeProgram $legislativeProgram
     * @param $event
     * @return void
     */
    private function sendEmails(LegislativeProgram $legislativeProgram, $event): void
    {
        if($event == 'created'){
            $administrators = null;
            $moderators = null;

            $subscribedUsers = UserSubscribe::where('id', 0)->get();

//            //get users by model ID
//            $subscribedUsers = UserSubscribe::where('subscribable_type', LegislativeProgram::class)
//                ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
//                ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
//                ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
//                ->where('subscribable_id', '=', $legislativeProgram->id)
//                ->get();

            //get users by model filter
            $filterSubscribtions = UserSubscribe::where('subscribable_type', LegislativeProgram::class)
                ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                ->whereNull('subscribable_id')
                ->get();

            if($filterSubscribtions->count()){
                foreach ($filterSubscribtions as $fSubscribe){
                    $filterArray = is_null($fSubscribe->search_filters) ? [] : json_decode($fSubscribe->search_filters, true);
                    $modelIds = LegislativeProgram::list($filterArray)->pluck('id')->toArray();
                    if(in_array($legislativeProgram->id, $modelIds)){
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
            $data['modelInstance'] = $legislativeProgram;
            $data['modelName'] = $legislativeProgram->name;
            $data['markdown'] = 'lp';

            SendSubscribedUserEmailJob::dispatch($data);
        }
    }
}
