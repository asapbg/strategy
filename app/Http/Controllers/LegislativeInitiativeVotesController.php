<?php

namespace App\Http\Controllers;

use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Models\LegislativeInitiative;
use App\Models\LegislativeInitiativeVote;
use App\Models\User;
use App\Notifications\LegislativeInitiativeSuccessful;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LegislativeInitiativeVotesController extends Controller
{

    public function store(LegislativeInitiative $legislativeInitiative, string $stat)
    {
        if (!auth()->user()) {
            return back()->with('warning', __('messages.action_only_registered'));
        }

        if (auth()->user()->cannot('vote', $legislativeInitiative)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $is_like = $stat == 'like';

        DB::beginTransaction();
        try {
            if ($legislativeInitiative->userHasLike() || $legislativeInitiative->userHasDislike()) {
                $this->revert($legislativeInitiative);
            }

            $new = new LegislativeInitiativeVote();
            $new->legislative_initiative_id = $legislativeInitiative->id;
            $new->user_id = auth()->user()->id;
            $new->is_like = $is_like;
            $new->save();

            $legislativeInitiative->refresh();
            if ($legislativeInitiative->cap == $legislativeInitiative->countSupport()) {
                $legislativeInitiative->status = LegislativeInitiativeStatusesEnum::STATUS_SEND->value;
                $legislativeInitiative->ready_to_send = 1;
                $legislativeInitiative->end_support_at = Carbon::now()->format('Y-m-d H:i:s');
                $legislativeInitiative->save();

                //Send notification to author and all voted for successful initiative
                $likesUserIds = $legislativeInitiative->likes->pluck('user_id')->toArray();
                if (sizeof($likesUserIds)) {
                    $users = User::whereIn('id', $likesUserIds)->get();
                    if ($users->count()) {
                        foreach ($users as $n) {
                            $n->notify(new LegislativeInitiativeSuccessful($legislativeInitiative));
                        }
                    }
                }
                if ($legislativeInitiative->user && !in_array($legislativeInitiative->user->id, $likesUserIds)) {
                    $legislativeInitiative->user->notify(new LegislativeInitiativeSuccessful($legislativeInitiative));
                }
            }
            DB::commit();
            return redirect()->back()->with('success', __('site.success_vote'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function revert(LegislativeInitiative $legislativeInitiative)
    {
        if (!auth()->user() || auth()->user()->cannot('vote', $legislativeInitiative)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        try {
            $stat = $legislativeInitiative->votes->first(fn($vote) => $vote->user_id === auth()->user()->id);

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
