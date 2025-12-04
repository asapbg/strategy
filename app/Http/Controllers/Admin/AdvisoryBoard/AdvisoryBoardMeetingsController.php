<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\NotifyAdvisoryBoardMeetingRequest;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardMeetingsRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardMeetingsRequest;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMeeting;
use App\Services\Notifications;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdvisoryBoardMeetingsController extends AdminController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param AdvisoryBoard $item
     * @param AdvisoryBoardMeeting $meeting
     *
     * @return JsonResponse
     */
    public function ajaxStore(Request $request, AdvisoryBoard $item, AdvisoryBoardMeeting $meeting)
    {
        $req = new StoreAdvisoryBoardMeetingsRequest();
        $validator = Validator::make($request->all(), $req->rules());
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 200);
        }
        $validated = $validator->validated();

        $this->authorize('update', $item);
        DB::beginTransaction();
        try {

            //$changes = $this->mainChanges(AdvisoryBoardMeeting::CHANGEABLE_FIELDS, $meeting, $validated);
            $meeting->fill(['advisory_board_id' => $item->id, 'next_meeting' => Carbon::parse($validated['next_meeting'])]);
            //$changes = array_merge($this->translateChanges(AdvisoryBoardMeeting::TRANSLATABLE_FIELDS, $meeting, $validated), $changes);//use it to send detail changes in notification
            $meeting->save();

            $validated['advisory_board_meeting_id'] = $meeting->id;
            $this->storeTranslateOrNew(AdvisoryBoardMeeting::TRANSLATABLE_FIELDS, $meeting, $validated);

            DB::commit();

            $notifyService = new Notifications();
            $notifyService->advChanges($item, request()->user(), 'Заседания');

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AdvisoryBoard $item
     * @param AdvisoryBoardMeeting $meeting
     *
     * @return JsonResponse
     */
    public function ajaxEdit(AdvisoryBoard $item, AdvisoryBoardMeeting $meeting)
    {
        return response()->json($meeting);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdvisoryBoardMeetingsRequest $request
     * @param AdvisoryBoard $item
     *
     * @return JsonResponse
     */
    public function ajaxUpdate(UpdateAdvisoryBoardMeetingsRequest $request, AdvisoryBoard $item)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $meeting = AdvisoryBoardMeeting::find($validated['meeting_id']);
            $validated['next_meeting'] = Carbon::parse($validated['next_meeting']);
            $fillable = $this->getFillableValidated($validated, $meeting);
            $meeting->fill($fillable);
            $meeting->save();

            $real_update = false;
            $dirty = $meeting->getDirty();

            $original_trans = $meeting->translation ? $meeting->translation->getOriginal() : [];
            $this->storeTranslateOrNew(AdvisoryBoardMeeting::TRANSLATABLE_FIELDS, $meeting, $validated);
            $new_trans = $meeting->translation ? $meeting->translation->toArray() : [];
            $t_dirty = array_diff_assoc($original_trans, $new_trans);
            unset($dirty['updated_at'], $t_dirty['updated_at']);
            if (count($dirty) || count($t_dirty)) {
                $real_update = true;
            }

            //alert adb board modeRATOR
            if ($real_update) {
                $notifyService = new Notifications();
                $notifyService->advChanges($item, request()->user(), 'Заседания');
            }

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Send notification to all members.
     *
     * @param NotifyAdvisoryBoardMeetingRequest $request
     * @param AdvisoryBoard $item
     *
     * @return JsonResponse
     */
    public function ajaxSendNotify(NotifyAdvisoryBoardMeetingRequest $request, AdvisoryBoard $item)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $meeting = AdvisoryBoardMeeting::find($validated['meeting_id']);

            $members_to_notify = $item->members()->where('email', '!=', null)->get();

            $link = $validated['additional_information_link'] ?? '';

            $include_files = $validated['include_files'] ?? false;

            foreach ($members_to_notify as $member) {
                $member->notify(new \App\Notifications\AdvisoryBoardMeeting($item, $meeting, $link, $include_files));

//                Notification::route('email', $member->email)->notify(new \App\Notifications\AdvisoryBoardMeeting($item, $meeting, $link, $include_files));
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AdvisoryBoard $item
     * @param AdvisoryBoardMeeting $meeting
     *
     * @return RedirectResponse
     */
    public function destroy(AdvisoryBoard $item, AdvisoryBoardMeeting $meeting)
    {
        $this->authorize('update', [AdvisoryBoard::class, $item]);

        $route = route('admin.advisory-boards.edit', $item->id) . '#decisions';

        try {
            $meeting->delete();

            return redirect($route)
                ->with('success', trans_choice('custom.meetings', 1) . ' ' . __('messages.deleted_successfully_n'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->to($route)->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param AdvisoryBoard $item
     * @param AdvisoryBoardMeeting $meeting
     *
     * @return RedirectResponse
     */
    public function restore(AdvisoryBoard $item, AdvisoryBoardMeeting $meeting)
    {
        $this->authorize('restore', [AdvisoryBoard::class, $item]);

        $route = route('admin.advisory-boards.edit', $item->id) . '#decisions';

        try {
            $meeting->restore();

            return redirect($route)
                ->with('success', trans_choice('custom.meetings', 1) . ' ' . __('messages.restored_successfully_n'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->to($route)->with('danger', __('messages.system_error'));
        }
    }
}
