<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\DeleteAdvisoryBoardMemberRequest;
use App\Http\Requests\Admin\AdvisoryBoard\RestoreAdvisoryBoardMemberRequest;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardMemberRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardMemberRequest;
use App\Models\AdvisoryBoardMember;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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

            if(isset($validated['is_member']) && $validated['advisory_type_id'] == 4){
                $validated['advisory_type_id'] = 2;
                $member = new AdvisoryBoardMember();
                $fillable = $this->getFillableValidated($validated, $member);
                $member->fill($fillable);
                $member->save();
                $this->storeTranslateOrNew(AdvisoryBoardMember::TRANSLATABLE_FIELDS, $member, $validated);
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
     * Show the form for editing the specified resource.
     *
     * @param AdvisoryBoardMember $member
     *
     * @return JsonResponse
     */
    public function ajaxEdit(AdvisoryBoardMember $member): JsonResponse
    {
        $this->authorize('update', $member);

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
     * @param DeleteAdvisoryBoardMemberRequest $request
     * @param AdvisoryBoardMember              $member
     *
     * @return RedirectResponse
     */
    public function destroy(DeleteAdvisoryBoardMemberRequest $request, AdvisoryBoardMember $member)
    {
        $route = route('admin.advisory-boards.edit', $member->advisory_board_id) . '#members';

        try {
            $member->delete();

            return redirect($route)
                ->with('success', trans_choice('custom.member', 1) . " $member->name " . __('messages.deleted_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->to($route)->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Restore the specified resource.
     */
    public function restore(RestoreAdvisoryBoardMemberRequest $request, AdvisoryBoardMember $member)
    {
        $route = route('admin.advisory-boards.edit', $member->advisory_board_id) . '#members';

        try {
            $member->restore();

            return redirect($route)
                ->with('success', trans_choice('custom.member', 1) . " $member->name " . __('messages.restored_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->to($route)->with('danger', __('messages.system_error'));
        }
    }
}
