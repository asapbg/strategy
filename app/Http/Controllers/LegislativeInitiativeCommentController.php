<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteLegislativeInitiativeCommentRequest;
use App\Models\LegislativeInitiative;
use App\Models\LegislativeInitiativeComment;
use App\Http\Requests\StoreLegislativeInitiativeCommentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class LegislativeInitiativeCommentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLegislativeInitiativeCommentRequest $request
     *
     * @return RedirectResponse
     */
    public function store(StoreLegislativeInitiativeCommentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $item = LegislativeInitiative::find($validated['legislative_initiative_id']);

        if(!$item){
            return back()->with('warning', __('messages.record_not_found'));
        }

        if($request->user()->cannot('comment', $item)){
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $validated['user_id'] = auth()->user()->id;

            $new = new LegislativeInitiativeComment();
            $new->fill($validated);
            $new->save();

            return to_route('legislative_initiatives.view', )
                ->with('success', trans_choice('custom.comments', 1) . " " . __('messages.created_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteLegislativeInitiativeCommentRequest $request
     * @param LegislativeInitiativeComment              $comment
     *
     * @return RedirectResponse
     */
    public function destroy(DeleteLegislativeInitiativeCommentRequest $request, LegislativeInitiativeComment $comment)
    {
        try {

            if($request->user()->cannot('comment', $comment->initiative) || $comment->user_id != $request->user()->id){
                return back()->with('warning', __('messages.unauthorized'));
            }

            $comment->delete();

            return redirect()
                ->back()
                ->with('success', trans_choice('custom.comments', 1) . " " . __('messages.deleted_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }
}
