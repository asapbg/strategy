<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardMeetingDecisionRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardMeetingDecisionRequest;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMeetingDecision;
use App\Services\Notifications;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

class AdvisoryBoardMeetingDecisionController extends AdminController
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
     * @param Request $request
     * @param AdvisoryBoard                            $item
     * @param AdvisoryBoardMeetingDecision             $decision
     *
     * @return JsonResponse
     */
    public function ajaxStore(Request $request, AdvisoryBoard $item, AdvisoryBoardMeetingDecision $decision)
    {
        $req = new StoreAdvisoryBoardMeetingDecisionRequest();
        $validator = Validator::make($request->all(), $req->rules());
        if($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 200);
        }
        $validated = $validator->validated();

        $this->authorize('update', $item);

        DB::beginTransaction();
        try {
            $validated['date_of_meeting'] = Carbon::parse($validated['date_of_meeting']);

            $fillable = $this->getFillableValidated($validated, $decision);

            $decision->fill($fillable);
            $decision->save();

            $validated['advisory_board_meeting_decision_id'] = $decision->id;

            $this->storeTranslateOrNew(AdvisoryBoardMeetingDecision::TRANSLATABLE_FIELDS, $decision, $validated);

            DB::commit();

            //alert adb board modeRATOR
            $notifyService = new Notifications();
            $notifyService->advChanges($item, request()->user());

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
     * @param \App\Models\AdvisoryBoardMeetingDecision $advisoryBoardMeetingDecision
     *
     * @return \Illuminate\Http\Response
     */
    public function show(AdvisoryBoardMeetingDecision $advisoryBoardMeetingDecision)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AdvisoryBoardMeetingDecision $advisoryBoardMeetingDecision
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(AdvisoryBoardMeetingDecision $advisoryBoardMeetingDecision)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardMeetingDecisionRequest $request
     * @param \App\Models\AdvisoryBoardMeetingDecision                                         $advisoryBoardMeetingDecision
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdvisoryBoardMeetingDecisionRequest $request, AdvisoryBoardMeetingDecision $advisoryBoardMeetingDecision)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AdvisoryBoardMeetingDecision $advisoryBoardMeetingDecision
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvisoryBoardMeetingDecision $advisoryBoardMeetingDecision)
    {
        //
    }
}
