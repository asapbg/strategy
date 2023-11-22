<?php

namespace App\Http\Controllers;


use App\Models\LegislativeInitiativeComment;
use App\Models\LegislativeInitiativeCommentStat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class LegislativeInitiativeCommentStatController extends Controller
{

    /**
     * Store comment's vote, like or dislike.
     *
     * @param LegislativeInitiativeComment $comment
     * @param string                       $stat
     *
     * @return RedirectResponse
     */
    public function store(LegislativeInitiativeComment $comment, string $stat)
    {
        $is_like = $stat == 'like';

        try {
            if ($comment->userHasLike() || $comment->userHasDislike()) {
                $this->revert($comment);
            }

            $new = new LegislativeInitiativeCommentStat();
            $new->comment_id = $comment->id;
            $new->user_id = auth()->user()->id;
            $new->is_like = $is_like;
            $new->save();

            return redirect()->back();
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Revert the first vote found for comment.
     *
     * @param LegislativeInitiativeComment $comment
     *
     * @return RedirectResponse
     */
    public function revert(LegislativeInitiativeComment $comment)
    {
        try {
            $stat = $comment->stats->first(fn($stat) => $stat->user_id === auth()->user()->id);

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
