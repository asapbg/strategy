<?php

namespace App\Http\Controllers;

use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Models\LegislativeInitiative;
use App\Models\LegislativeInitiativeVote;
use App\Models\User;
use App\Notifications\LegislativeInitiativeSuccessful;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LegislativeInitiativeVotesController extends Controller
{

    public function store(LegislativeInitiative $item, string $stat)
    {
        if(!auth()->user()){
            return back()->with('warning', __('messages.action_only_registered'));
        }

        if(auth()->user()->cannot('vote', $item)){
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
            if($item->cap == $item->countSupport()) {
                $item->status = LegislativeInitiativeStatusesEnum::STATUS_SEND->value;
                $item->ready_to_send = 1;
                $item->end_support_at = Carbon::now()->format('Y-m-d H:i:s');
                $item->save();

                //Send notification to author and all voted for successful initiative
                $likesUserIds = $item->likes->pluck('user_id')->toArray();
                if(sizeof($likesUserIds)){
                    $users = User::whereIn('id', $likesUserIds)->get();
                    if($users->count()){
                        foreach ($users as $n){
                            $n->notify(new LegislativeInitiativeSuccessful($item));
                        }
                    }
                }
                if($item->user){
                    $item->user->notify(new LegislativeInitiativeSuccessful($item));
                }
            }
            \DB::commit();
            return redirect()->back()->with('success', __('site.success_vote'));
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
