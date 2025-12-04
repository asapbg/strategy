<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardMeetingDecisionRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardMeetingDecisionRequest;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMeetingDecision;
use App\Services\Notifications;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdvisoryBoardMeetingDecisionController extends AdminController
{
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
        $rules = $req->rules();

        if ($request->has('advisory_board_meeting_decision_id')) {
            $decision = AdvisoryBoardMeetingDecision::findOrFail($request->get('advisory_board_meeting_decision_id'));

            unset($rules['advisory_board_meeting_id']);
        }

        $validator = Validator::make($request->all(), $rules);
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
     * Show the form for editing the specified resource.
     *
     * @param AdvisoryBoard                $item
     * @param AdvisoryBoardMeetingDecision $decision
     *
     * @return JsonResponse
     */
    public function ajaxEdit(AdvisoryBoard $item, AdvisoryBoardMeetingDecision $decision)
    {
        return response()->json($decision);
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
}
