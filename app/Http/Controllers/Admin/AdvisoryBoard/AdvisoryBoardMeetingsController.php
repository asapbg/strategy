<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardMeetingsRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardMeetingsRequest;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMeeting;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Log;

class AdvisoryBoardMeetingsController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdvisoryBoardMeetingsRequest $request
     * @param AdvisoryBoard                     $item
     * @param AdvisoryBoardMeeting              $meeting
     *
     * @return JsonResponse
     */
    public function ajaxStore(StoreAdvisoryBoardMeetingsRequest $request, AdvisoryBoard $item, AdvisoryBoardMeeting $meeting)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $meeting->fill(['advisory_board_id' => $item->id, 'next_meeting' => Carbon::parse($validated['next_meeting'])]);
            $meeting->save();

            $validated['advisory_board_meeting_id'] = $meeting->id;

            $this->storeTranslateOrNew(AdvisoryBoardMeeting::TRANSLATABLE_FIELDS, $meeting, $validated);

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AdvisoryBoardMeeting $advisoryBoardMeetings
     *
     * @return \Illuminate\Http\Response
     */
    public function show(AdvisoryBoardMeeting $advisoryBoardMeetings)
    {
        //
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
            $meeting->fill($fillable);
            $meeting->save();

            $this->storeTranslateOrNew(AdvisoryBoardMeeting::TRANSLATABLE_FIELDS, $meeting, $validated);

            DB::commit();

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
                ->with('success', trans_choice('custom.meetings', 1) . ' ' . __('messages.deleted_successfully_n'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->to($route)->with('danger', __('messages.system_error'));
        }
    }
}
