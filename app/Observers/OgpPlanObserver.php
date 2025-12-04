<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Library\Facebook;
use App\Models\OgpPlan;
use App\Models\OgpStatus;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class OgpPlanObserver
{
    /**
     * Handle the OgpPlan "created" event.
     *
     * @param \App\Models\OgpPlan $ogpPlan
     * @return void
     */
    public function created(OgpPlan $ogpPlan)
    {
//        if (!env('DISABLE_OBSERVERS', false)) {
//            if (
//                $ogpPlan->active
//                && $ogpPlan->national_plan
//                && $ogpPlan->ogp_status_id == OgpStatus::activeStatus()->first()->id
//            ) {
//                $this->sendEmails($ogpPlan, 'created');
//            }
//        }
    }

    /**
     * Handle the OgpPlan "updated" event.
     *
     * @param \App\Models\OgpPlan $ogpPlan
     * @return void
     */
    public function updated(OgpPlan $ogpPlan)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            $old_ogp_status = $ogpPlan->getOriginal('ogp_status_id');
            $old_active = $ogpPlan->getOriginal('active');
            $report_evaluation_published_at = $ogpPlan->getOriginal('report_evaluation_published_at');

            $fbPost = [];
            if (
                $ogpPlan->active && !$ogpPlan->national_plan
                && $old_ogp_status != $ogpPlan->ogp_status_id
                && ($ogpPlan->ogp_status_id == OgpStatus::InDevelopment()->first()->id)
            ) {
                $message = "Започва изготвянето на план по Партньорството за отворено управление: $ogpPlan->name.";
                $fbPost = array(
                    'message' => "$message Приемат се коментари до " . displayDate($ogpPlan->to_date_develop) . '. За повече информация тук.',
                    'link' => route('ogp.develop_new_action_plans', $ogpPlan->id),
                    'published' => true
                );
                Log::channel('info')->info("Post ogp plan to facebook: $message");
            }

            if (
                $ogpPlan->active && $ogpPlan->national_plan
                && $old_ogp_status != $ogpPlan->ogp_status_id
                && ($ogpPlan->ogp_status_id == OgpStatus::activeStatus()->first()->id || $ogpPlan->ogp_status_id == OgpStatus::Final()->first()->id)
            ) {
                $message = "Приключи изготвянето на план по Партньорството за отворено управление: $ogpPlan->name.";
                $fbPost = array(
                    'message' => "$message За повече информация тук.",
                    'link' => route('ogp.national_action_plans.show', $ogpPlan->id),
                    'published' => true
                );
                Log::channel('info')->info("Post ogp plan to facebook: $message");
            }

            if (
                (is_null($report_evaluation_published_at) && $report_evaluation_published_at != $ogpPlan->report_evaluation_published_at)
                && $ogpPlan->active && $ogpPlan->national_plan
                && ($ogpPlan->ogp_status_id == OgpStatus::activeStatus()->first()->id || $ogpPlan->ogp_status_id == OgpStatus::Final()->first()->id)
            ) {
                $message = "Публикуван е доклад по $ogpPlan->name в рамките на Партньорство за отворено управление.";
                $fbPost = array(
                    'message' => "$message За повече информация тук.",
                    'link' => route('ogp.national_action_plans.show', $ogpPlan->id),
                    'published' => true
                );
                $this->sendEmails($ogpPlan, 'created_report');
                Log::channel('info')->info("Post ogp plan to facebook: $message");
            }

            if (sizeof($fbPost) && Setting::allowPostingToFacebook()) {
                $facebookApi = new Facebook();
                $facebookApi->postOnPage($fbPost);
            }

            if (
                ($old_active != $ogpPlan->active || $old_ogp_status != $ogpPlan->ogp_status_id)
                && $ogpPlan->active && $ogpPlan->national_plan
                && ($ogpPlan->ogp_status_id == OgpStatus::activeStatus()->first()->id || $ogpPlan->ogp_status_id == OgpStatus::Final()->first()->id)
            ) {
                $this->sendEmails($ogpPlan, 'created');
            }
        }
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
        $data['event'] = $event;
        $data['modelInstance'] = $ogpPlan;
        $data['modelName'] = $ogpPlan->name;
        $data['markdown'] = $event == 'created_report' ? 'ogp_report' : 'ogp';

        //SendSubscribedUserEmailJob::dispatch($data);
    }
}
