<?php

namespace App\Observers;

use App\Library\Facebook;
use App\Models\OgpPlan;
use App\Models\OgpStatus;
use App\Models\Setting;

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
        //
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

        if($ogpPlan->active
            && !$old_ogp_status != $ogpPlan->ogp_status_id
            && $ogpPlan->ogp_status_id == OgpStatus::activeStatus()->first()->id) {
            //TODO post on facebook
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
            //TODO post on twitter
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
}
