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
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Log;

class AdvisoryBoardMeetingsController extends AdminController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param AdvisoryBoard                     $item
     * @param AdvisoryBoardMeeting              $meeting
     *
     * @return JsonResponse
     */
    public function ajaxStore(Request $request, AdvisoryBoard $item, AdvisoryBoardMeeting $meeting)
    {
        $req = new StoreAdvisoryBoardMeetingsRequest();
        $validator = Validator::make($request->all(), $req->rules());
        if($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 200);
        }
        $validated = $validator->validated();

        $this->authorize('update', $item);
        DB::beginTransaction();
        try {

            $changes = $this->mainChanges(AdvisoryBoardMeeting::CHANGEABLE_FIELDS, $meeting, $validated);
            $meeting->fill(['advisory_board_id' => $item->id, 'next_meeting' => Carbon::parse($validated['next_meeting'])]);
            $changes = array_merge($this->translateChanges(AdvisoryBoardMeeting::TRANSLATABLE_FIELDS, $meeting, $validated), $changes);//use it to send detail changes in notification
            $meeting->save();

            $validated['advisory_board_meeting_id'] = $meeting->id;
            $this->storeTranslateOrNew(AdvisoryBoardMeeting::TRANSLATABLE_FIELDS, $meeting, $validated);

            DB::commit();

            //alert adb board modeRATOR
            if(sizeof($changes)){
                $notifyService = new Notifications();
                $notifyService->advChanges($item, request()->user(), 'Заседания', $changes);
            }

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
     * @param AdvisoryBoard        $item
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
     * @param AdvisoryBoard                      $item
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
            $changes = $this->mainChanges(AdvisoryBoardMeeting::CHANGEABLE_FIELDS, $meeting, $validated);
            $meeting->fill($fillable);
            $changes = array_merge($this->translateChanges(AdvisoryBoardMeeting::TRANSLATABLE_FIELDS, $meeting, $validated), $changes);//use it to send detail changes in notification

            $meeting->save();

            $this->storeTranslateOrNew(AdvisoryBoardMeeting::TRANSLATABLE_FIELDS, $meeting, $validated);

            DB::commit();

            //alert adb board modeRATOR
            if(sizeof($changes)){
                $notifyService = new Notifications();
                $notifyService->advChanges($item, request()->user(), 'Заседания', $changes);
            }

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
     * @param AdvisoryBoard                     $item
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
     * @param AdvisoryBoard        $item
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
     * @param AdvisoryBoard        $item
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
