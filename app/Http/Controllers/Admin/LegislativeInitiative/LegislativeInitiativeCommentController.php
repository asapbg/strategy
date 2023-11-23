<?php

namespace App\Http\Controllers\Admin\LegislativeInitiative;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LegislativeInitiative\AdminDeleteLegislativeInitiativeCommentRequest;
use App\Models\LegislativeInitiativeComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class LegislativeInitiativeCommentController extends Controller
{

    /**
     * Remove the specified resource from storage.
     *
     * @param AdminDeleteLegislativeInitiativeCommentRequest $request
     * @param LegislativeInitiativeComment                   $comment
     *
     * @return RedirectResponse
     */
    public function destroy(AdminDeleteLegislativeInitiativeCommentRequest $request, LegislativeInitiativeComment $comment)
    {
        try {
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
