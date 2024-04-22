<?php

namespace App\Observers;

use App\Models\OgpAreaOffer;
use App\Models\OgpPlanAreaOffer;
use App\Models\OgpPlanAreaOfferVote;
use Illuminate\Support\Facades\DB;

class OgpPlanAreaOfferVoteObserver
{

    public $afterCommit = true;

    /**
     * Handle the OgpAreaOfferVote "created" event.
     *
     * @param  \App\Models\OgpPlanAreaOfferVote  $ogpAreaOfferVote
     * @return void
     */
    public function created(OgpPlanAreaOfferVote $ogpAreaOfferVote)
    {
        if(!env('DISABLE_OBSERVERS', false)) {
            $offer_id = $ogpAreaOfferVote->ogp_plan_area_offer_id;

            $item = OgpPlanAreaOfferVote::select(
                DB::raw('coalesce(sum(case when is_like = true then 1 end), 0) as likes_cnt'),
                DB::raw('coalesce(sum(case when is_like = false then 1 end), 0) as dislikes_cnt'),
            )
                ->where('ogp_plan_area_offer_id', '=', $offer_id)
                ->first();

            OgpPlanAreaOffer::where('id', '=', $offer_id)->update($item->toArray());
        }
    }

    /**
     * Handle the OgpAreaOfferVote "updated" event.
     *
     * @param  \App\Models\OgpPlanAreaOfferVote  $ogpAreaOfferVote
     * @return void
     */
    public function updated(OgpPlanAreaOfferVote $ogpAreaOfferVote)
    {
        //
    }

    /**
     * Handle the OgpAreaOfferVote "deleted" event.
     *
     * @param  \App\Models\OgpPlanAreaOfferVote  $ogpAreaOfferVote
     * @return void
     */
    public function deleted(OgpPlanAreaOfferVote $ogpAreaOfferVote)
    {
        //
    }

    /**
     * Handle the OgpAreaOfferVote "restored" event.
     *
     * @param  \App\Models\OgpPlanAreaOfferVote  $ogpAreaOfferVote
     * @return void
     */
    public function restored(OgpPlanAreaOfferVote $ogpAreaOfferVote)
    {
        //
    }

    /**
     * Handle the OgpAreaOfferVote "force deleted" event.
     *
     * @param  \App\Models\OgpPlanAreaOfferVote  $ogpAreaOfferVote
     * @return void
     */
    public function forceDeleted(OgpPlanAreaOfferVote $ogpAreaOfferVote)
    {
        //
    }
}
