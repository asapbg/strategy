<?php

namespace App\Observers;

use App\Models\OgpAreaOffer;
use App\Models\OgpAreaOfferVote;
use Illuminate\Support\Facades\DB;

class OgpAreaOfferVoteObserver
{

    public $afterCommit = true;

    /**
     * Handle the OgpAreaOfferVote "created" event.
     *
     * @param  \App\Models\OgpAreaOfferVote  $ogpAreaOfferVote
     * @return void
     */
    public function created(OgpAreaOfferVote $ogpAreaOfferVote)
    {
        $offer_id = $ogpAreaOfferVote->ogp_area_offer_id;

        $item = OgpAreaOfferVote::select(
            DB::raw('coalesce(sum(case when is_like = true then 1 end), 0) as likes_cnt'),
            DB::raw('coalesce(sum(case when is_like = false then 1 end), 0) as dislikes_cnt'),
        )
            ->where('ogp_area_offer_id', '=', $offer_id)
            ->first();

        OgpAreaOffer::where('id', '=', $offer_id)->update($item->toArray());
    }

    /**
     * Handle the OgpAreaOfferVote "updated" event.
     *
     * @param  \App\Models\OgpAreaOfferVote  $ogpAreaOfferVote
     * @return void
     */
    public function updated(OgpAreaOfferVote $ogpAreaOfferVote)
    {
        //
    }

    /**
     * Handle the OgpAreaOfferVote "deleted" event.
     *
     * @param  \App\Models\OgpAreaOfferVote  $ogpAreaOfferVote
     * @return void
     */
    public function deleted(OgpAreaOfferVote $ogpAreaOfferVote)
    {
        //
    }

    /**
     * Handle the OgpAreaOfferVote "restored" event.
     *
     * @param  \App\Models\OgpAreaOfferVote  $ogpAreaOfferVote
     * @return void
     */
    public function restored(OgpAreaOfferVote $ogpAreaOfferVote)
    {
        //
    }

    /**
     * Handle the OgpAreaOfferVote "force deleted" event.
     *
     * @param  \App\Models\OgpAreaOfferVote  $ogpAreaOfferVote
     * @return void
     */
    public function forceDeleted(OgpAreaOfferVote $ogpAreaOfferVote)
    {
        //
    }
}
