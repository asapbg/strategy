<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreAdvisoryBoardMemberRequest;
use App\Http\Requests\UpdateAdvisoryBoardMemberRequest;
use App\Models\AdvisoryBoardMember;
use DB;
use Illuminate\Http\JsonResponse;
use Log;

class AdvisoryBoardMemberController extends AdminController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdvisoryBoardMemberRequest $request
     *
     * @return JsonResponse
     */
    public function ajaxStore(StoreAdvisoryBoardMemberRequest $request): JsonResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $item = new AdvisoryBoardMember();
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();

            $validated['advisory_member_id'] = $item->id;

            $this->storeTranslateOrNew(AdvisoryBoardMember::TRANSLATABLE_FIELDS, $item, $validated);
            DB::commit();

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
     * @param AdvisoryBoardMember $member
     *
     * @return JsonResponse
     */
    public function ajaxEdit(AdvisoryBoardMember $member): JsonResponse
    {
        return response()->json($member);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdvisoryBoardMemberRequest $request
     *
     * @return JsonResponse
     */
    public function ajaxUpdate(UpdateAdvisoryBoardMemberRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $item = AdvisoryBoardMember::find($validated['advisory_board_member_id']);
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();

            $validated['advisory_member_id'] = $item->id;

            $this->storeTranslateOrNew(AdvisoryBoardMember::TRANSLATABLE_FIELDS, $item, $validated);
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
     * @param \App\Models\AdvisoryBoardMember $advisoryBoardMember
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvisoryBoardMember $advisoryBoardMember)
    {
        //
    }
}
