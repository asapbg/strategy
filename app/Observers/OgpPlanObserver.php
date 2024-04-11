<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Library\Facebook;
use App\Models\OgpPlan;
use App\Models\OgpStatus;
use App\Models\Setting;
use App\Models\UserSubscribe;
use Illuminate\Support\Facades\Log;

class OgpPlanObserver
{
    /**
     * Handle the OgpPlan "created" event.
     *
     * @param  \App\Models\OgpPlan  $ogpPlan
     * @return void
     */
    public function created(OgpPlan $ogpPlan)
    {
        if($ogpPlan->active && $ogpPlan->national_plan && $ogpPlan->ogp_status_id == OgpStatus::activeStatus()->first()->id){
            $this->sendEmails($ogpPlan, 'created');
            Log::info('Send subscribe email on creation');
        }
    }

    /**
     * Handle the OgpPlan "updated" event.
     *
     * @param  \App\Models\OgpPlan  $ogpPlan
     * @return void
     */
    public function updated(OgpPlan $ogpPlan)
    {
        $old_ogp_status = $ogpPlan->getOriginal('ogp_status_id');
        $old_active = $ogpPlan->getOriginal('active');
        $report_evaluation_published_at = $ogpPlan->getOriginal('report_evaluation_published_at');

        if(
            $ogpPlan->active
            && !$old_ogp_status != $ogpPlan->ogp_status_id
            && ($ogpPlan->ogp_status_id == OgpStatus::activeStatus()->first()->id || $ogpPlan->ogp_status_id == OgpStatus::Final()->first()->id)
        ) {
            //post on facebook
            $activeFB = Setting::where('section', '=', Setting::FACEBOOK_SECTION)
                ->where('name', '=', Setting::FACEBOOK_IS_ACTIVE)
                ->get()->first();
            if($activeFB->value){
                $facebookApi = new Facebook();
                $facebookApi->postOnPage(array(
                    'message' => 'Публикуван е нов Национален план: '.$ogpPlan->name,
                    'link' => route('ogp.national_action_plans.show', $ogpPlan->id),
                    'published' => true
                ));
            }
        }

        if(
            ($old_active != $ogpPlan->active || $old_ogp_status != $ogpPlan->ogp_status_id)
            && $ogpPlan->active && $ogpPlan->national_plan
            && ($ogpPlan->ogp_status_id == OgpStatus::activeStatus()->first()->id || $ogpPlan->ogp_status_id == OgpStatus::Final()->first()->id)
        ){
            $this->sendEmails($ogpPlan, 'created');
            Log::info('Send subscribe email on creation');
        }

        if(
            (is_null($report_evaluation_published_at) && $report_evaluation_published_at != $ogpPlan->report_evaluation_published_at)
            && $ogpPlan->active && $ogpPlan->national_plan
            && ($ogpPlan->ogp_status_id == OgpStatus::activeStatus()->first()->id || $ogpPlan->ogp_status_id == OgpStatus::Final()->first()->id)
        ){
            $this->sendEmails($ogpPlan, 'created_report');
            Log::info('Send subscribe email on creation');
        }
    }

    /**
     * Handle the OgpPlan "deleted" event.
     *
     * @param  \App\Models\OgpPlan  $ogpPlan
     * @return void
     */
    public function deleted(OgpPlan $ogpPlan)
    {
        //
    }

    /**
     * Handle the OgpPlan "restored" event.
     *
     * @param  \App\Models\OgpPlan  $ogpPlan
     * @return void
     */
    public function restored(OgpPlan $ogpPlan)
    {
        //
    }

    /**
     * Handle the OgpPlan "force deleted" event.
     *
     * @param  \App\Models\OgpPlan  $ogpPlan
     * @return void
     */
    public function forceDeleted(OgpPlan $ogpPlan)
    {
        //
    }

    /**
     * Send emails
     *
     * @param OgpPlan $ogpPlan
     * @param $event
     * @return void
     */
    private function sendEmails(OgpPlan $ogpPlan, $event): void
    {
        $administrators = null;
        $moderators = null;
        //get users by model ID
        $subscribedUsers = UserSubscribe::where('subscribable_type', OgpPlan::class)
            ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
            ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
            ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
            ->where('subscribable_id', '=', $ogpPlan->id)
            ->get();

        //get users by model filter
        $filterSubscribtions = UserSubscribe::where('subscribable_type', OgpPlan::class)
            ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
            ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
            ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
            ->whereNull('subscribable_id')
            ->get();

        if($filterSubscribtions->count()){
            foreach ($filterSubscribtions as $fSubscribe){
                $filterArray = is_null($fSubscribe->search_filters) ? [] : json_decode($fSubscribe->search_filters, true);
                $modelIds = OgpPlan::list($filterArray)->pluck('id')->toArray();
                if(in_array($ogpPlan->id, $modelIds)){
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
        $data['modelInstance'] = $ogpPlan;
        $data['markdown'] = $event == 'created_report' ? 'ogp_report' : 'ogp';

        SendSubscribedUserEmailJob::dispatch($data);

    }
}
