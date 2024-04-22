<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\Consultations\OperationalProgram;
use App\Models\UserSubscribe;
use Illuminate\Support\Facades\Log;

class OperationalProgramObserver
{
    /**
     * Handle the OperationalProgram "created" event.
     *
     * @param  \App\Models\Consultations\OperationalProgram  $operationalProgram
     * @return void
     */
    public function created(OperationalProgram $operationalProgram)
    {
        if(!env('DISABLE_OBSERVERS', false)) {
            if ($operationalProgram->public) {
                $this->sendEmails($operationalProgram, 'created');
                Log::info('Send subscribe email on creation');
            }
        }
    }

    /**
     * Handle the OperationalProgram "updated" event.
     *
     * @param  \App\Models\Consultations\OperationalProgram  $operationalProgram
     * @return void
     */
    public function updated(OperationalProgram $operationalProgram)
    {
        if(!env('DISABLE_OBSERVERS', false)) {
            $old_public = (int)$operationalProgram->getOriginal('public');
            //Check for real changes
            $dirty = $operationalProgram->getDirty(); //return all changed fields
            //skip some fields in specific cases
            unset($dirty['updated_at']);

            if (sizeof($dirty) && !$old_public && $operationalProgram->public) {
                $this->sendEmails($operationalProgram, 'created');
                Log::info('Send subscribe email on update');
            }
        }
    }

    /**
     * Handle the OperationalProgram "deleted" event.
     *
     * @param  \App\Models\Consultations\OperationalProgram  $operationalProgram
     * @return void
     */
    public function deleted(OperationalProgram $operationalProgram)
    {
        //
    }

    /**
     * Handle the OperationalProgram "restored" event.
     *
     * @param  \App\Models\Consultations\OperationalProgram  $operationalProgram
     * @return void
     */
    public function restored(OperationalProgram $operationalProgram)
    {
        //
    }

    /**
     * Handle the OperationalProgram "force deleted" event.
     *
     * @param  \App\Models\Consultations\OperationalProgram  $operationalProgram
     * @return void
     */
    public function forceDeleted(OperationalProgram $operationalProgram)
    {
        //
    }

    /**
     * Send emails
     *
     * @param OperationalProgram $operationalProgram
     * @param $event
     * @return void
     */
    private function sendEmails(OperationalProgram $operationalProgram, $event): void
    {
        if($event == 'created'){
            $administrators = null;
            $moderators = null;
            //get users by model ID
            $subscribedUsers = UserSubscribe::where('subscribable_type', OperationalProgram::class)
                ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                ->where('subscribable_id', '=', $operationalProgram->id)
                ->get();

            //get users by model filter
            $filterSubscribtions = UserSubscribe::where('subscribable_type', OperationalProgram::class)
                ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                ->whereNull('subscribable_id')
                ->get();

            if($filterSubscribtions->count()){
                foreach ($filterSubscribtions as $fSubscribe){
                    $filterArray = is_null($fSubscribe->search_filters) ? [] : json_decode($fSubscribe->search_filters, true);
                    $modelIds = OperationalProgram::list($filterArray)->pluck('id')->toArray();
                    if(in_array($operationalProgram->id, $modelIds)){
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
            $data['modelInstance'] = $operationalProgram;
            $data['markdown'] = 'op';

            SendSubscribedUserEmailJob::dispatch($data);
        }
    }
}
