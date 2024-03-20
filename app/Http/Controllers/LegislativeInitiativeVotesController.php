<?php

namespace App\Http\Controllers;

use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Models\LegislativeInitiative;
use App\Models\LegislativeInitiativeVote;
use App\Notifications\SendLegislativeInitiative;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class LegislativeInitiativeVotesController extends Controller
{

    /**
     * Store like or dislike.
     *
     * @param LegislativeInitiative $item
     * @param string                $stat
     *
     * @return RedirectResponse
     */
    public function store(LegislativeInitiative $item, string $stat)
    {
        $is_like = $stat == 'like';

        try {
            if ($item->userHasLike() || $item->userHasDislike()) {
                $this->revert($item);
            }

            $new = new LegislativeInitiativeVote();
            $new->legislative_initiative_id = $item->id;
            $new->user_id = auth()->user()->id;
            $new->is_like = $is_like;
            $new->save();

            if($item->cap <= $item->countSupport()) {
                $item->ready_to_send = 1;
                $item->save();
            }

            return redirect()->back();
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Revert the first vote found in collection.
     *
     * @param LegislativeInitiative $item
     *
     * @return RedirectResponse
     */
    public function revert(LegislativeInitiative $item)
    {
        try {
            $stat = $item->votes->first(fn($vote) => $vote->user_id === auth()->user()->id);

            if ($stat) {
                $stat->delete();
            }

            return redirect()->back();
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }
}
