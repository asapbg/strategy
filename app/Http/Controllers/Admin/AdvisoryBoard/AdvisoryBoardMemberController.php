<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Enums\AdvisoryTypeEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\DeleteAdvisoryBoardMemberRequest;
use App\Http\Requests\Admin\AdvisoryBoard\RestoreAdvisoryBoardMemberRequest;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardMemberRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardMemberRequest;
use App\Http\Requests\StoreAdvBoardMembersOrderRequest;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMember;
use App\Services\Notifications;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdvisoryBoardMemberController extends AdminController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function ajaxStore(Request $request): JsonResponse
    {
//        $validated = $request->validated();

        $req = new StoreAdvisoryBoardMemberRequest();
        $validator = Validator::make($request->all(), $req->rules());
        if($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 200);
        }

        $validated = $validator->validated();

        DB::beginTransaction();
        try {
            $validated['is_advisory_board_member'] = $validated['is_advisory_board_member'] ?? false;

            $item = new AdvisoryBoardMember();
            $changes = $this->mainChanges(AdvisoryBoardMember::CHANGEABLE_FIELDS, $item, $validated);
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);

            $changes = array_merge($this->translateChanges(AdvisoryBoardMember::TRANSLATABLE_FIELDS, $item, $validated), $changes);//use it to send detail changes in notification
            $item->save();

            $validated['advisory_member_id'] = $item->id;
            $this->storeTranslateOrNew(AdvisoryBoardMember::TRANSLATABLE_FIELDS, $item, $validated);

//            if(isset($validated['is_member']) && $validated['advisory_type_id'] == 4){
//                $validated['advisory_type_id'] = 2;
//                $member = new AdvisoryBoardMember();
//                $fillable = $this->getFillableValidated($validated, $member);
//                $member->fill($fillable);
//                $member->save();
//                $this->storeTranslateOrNew(AdvisoryBoardMember::TRANSLATABLE_FIELDS, $member, $validated);
//            }
            DB::commit();

            //alert adb board modeRATOR
            $advBoard = AdvisoryBoard::find($validated['advisory_board_id']);
            if(sizeof($changes)){
                $notifyService = new Notifications();
                $notifyService->advChanges($advBoard, request()->user(), trans_choice('custom.member', 2).' - '.$item->member_name, $changes);
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
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function ajaxUpdate(Request $request)
    {
        $req = new UpdateAdvisoryBoardMemberRequest();
        $validator = Validator::make($request->all(), $req->rules());
        if($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 200);
        }

        $validated = $validator->validated();

        DB::beginTransaction();
        try {
            $item = AdvisoryBoardMember::find($validated['advisory_board_member_id']);
            $changes = $this->mainChanges(AdvisoryBoardMember::CHANGEABLE_FIELDS, $item, $validated);

            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);

            $changes = array_merge($this->translateChanges(AdvisoryBoardMember::TRANSLATABLE_FIELDS, $item, $validated), $changes);//use it to send detail changes in notification
            $item->save();

            $validated['advisory_member_id'] = $item->id;

            $this->storeTranslateOrNew(AdvisoryBoardMember::TRANSLATABLE_FIELDS, $item, $validated);
            DB::commit();

            //alert adb board modeRATOR
            $advBoard = AdvisoryBoard::find($validated['advisory_board_id']);
            if(sizeof($changes)){
                $notifyService = new Notifications();
                $notifyService->advChanges($advBoard, request()->user(), trans_choice('custom.member', 2).' - '.$item->member_name, $changes);
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
     * @param DeleteAdvisoryBoardMemberRequest $request
     * @param AdvisoryBoardMember              $member
     *
     * @return RedirectResponse
     */
    public function destroy(DeleteAdvisoryBoardMemberRequest $request, AdvisoryBoardMember $member)
    {
        $hash = AdvisoryTypeEnum::nameByValue($member->advisory_type_id);
        $route = route('admin.advisory-boards.edit', $member->advisory_board_id) . '#'.$hash;

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
        $hash = AdvisoryTypeEnum::nameByValue($member->advisory_type_id);
        $route = route('admin.advisory-boards.edit', $member->advisory_board_id) . '#'.$hash;

        try {
            $member->restore();

            return redirect($route)
                ->with('success', trans_choice('custom.member', 1) . " $member->name " . __('messages.restored_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->to($route)->with('danger', __('messages.system_error'));
        }
    }

    public function setOrder(StoreAdvBoardMembersOrderRequest $request)
    {
        $validated = $request->validated();
        $item = AdvisoryBoard::find($validated['id']);

        if(!$item) {
            abort(404);
        }

        $this->authorize('update', $item);

        DB::beginTransaction();
        try {

            foreach ($validated['member'] as $key => $member) {
                $itemMember = AdvisoryBoardMember::withTrashed()->find((int)$member);
                $itemMember->ord = $validated['member_ord'][$key];
                $itemMember->save();
            }
            DB::commit();
            $hash = AdvisoryTypeEnum::nameByValue($validated['type']);
            return redirect(route('admin.advisory-boards.edit', $item).'#'.$hash)->with('success', 'Промените бяха записани успешно');
        } catch (\Exception $e){
            DB::rollBack();
            Log::error('Update Adv board Members order: '.$e);
            return back()->withInput()->with('error', __('messages.system_error'));
        }


    }
}
