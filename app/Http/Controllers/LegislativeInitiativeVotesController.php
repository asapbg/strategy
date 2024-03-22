<?php

namespace App\Http\Controllers;

use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Models\LegislativeInitiative;
use App\Models\LegislativeInitiativeVote;
use App\Notifications\SendLegislativeInitiative;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LegislativeInitiativeVotesController extends Controller
{

    public function store(LegislativeInitiative $item, string $stat)
    {
        if(!auth()->user() || auth()->user()->cannot('vote', $item)){
            return back()->with('warning', __('messages.unauthorized'));
        }
        $is_like = $stat == 'like';

        \DB::beginTransaction();
        try {
            if ($item->userHasLike() || $item->userHasDislike()) {
                $this->revert($item);
            }

            $new = new LegislativeInitiativeVote();
            $new->legislative_initiative_id = $item->id;
            $new->user_id = auth()->user()->id;
            $new->is_like = $is_like;
            $new->save();

            $item->refresh();
            if($item->cap <= $item->countSupport()) {

                $item->status = LegislativeInitiativeStatusesEnum::STATUS_SEND->value;
                $item->ready_to_send = 1;
                $item->end_support_at = Carbon::now()->format('Y-m-d H:i:s');
                $item->save();
            }
            \DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function revert(LegislativeInitiative $item)
    {
        if(!auth()->user() || auth()->user()->cannot('vote', $item)){
            return back()->with('warning', __('messages.unauthorized'));
        }
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
