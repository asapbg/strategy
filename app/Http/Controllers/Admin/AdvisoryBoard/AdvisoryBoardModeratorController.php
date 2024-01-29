<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\StoreUserModeratorRequest;
use App\Http\Requests\StoreAdvisoryBoardModeratorInformationRequest;
use App\Http\Requests\StoreAdvisoryBoardModeratorRequest;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardModerator;
use App\Models\AdvisoryBoardModeratorInformation;
use App\Models\CustomRole;
use App\Models\User;
use App\Notifications\AdvBoardAssignedModerator;
use App\Services\Notifications;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

class AdvisoryBoardModeratorController extends AdminController
{

    /**
     * Store or update global moderator's information.
     *
     * @param Request $request
     * @param AdvisoryBoard $item
     * @param AdvisoryBoardModeratorInformation $information
     *
     * @return RedirectResponse
     */
    public function storeInformation(Request $request, AdvisoryBoard $item, AdvisoryBoardModeratorInformation $information)
    {
        $route = route('admin.advisory-boards.edit', $item->id) . '#moderator';
        $req = new StoreAdvisoryBoardModeratorInformationRequest();
        $validator = Validator::make($request->all(), $req->rules());
        if($validator->fails()) {
            return redirect($route)->withInput()->withErrors($validator->errors());
        }

        $validated = $validator->validated();
        $this->authorize('update', $item);


        DB::beginTransaction();
        try {
            $validated['advisory_board_id'] = $item->id;
            $fillable = $this->getFillableValidated($validated, $information);
            $information->fill($fillable);
            $information->save();

            $this->storeTranslateOrNew(AdvisoryBoardModeratorInformation::TRANSLATABLE_FIELDS, $information, $validated);

            DB::commit();

            //alert adb board modeRATOR
            $notifyService = new Notifications();
            $notifyService->advChanges($item, request()->user());

            return redirect($route)->with('success', trans_choice('custom.section', 1) . " " . __('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect($route)->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Store user as moderator.
     *
     * @param StoreAdvisoryBoardModeratorRequest $request
     * @param AdvisoryBoard                      $item
     * @param AdvisoryBoardModerator             $moderator
     *
     * @return RedirectResponse
     */
    public function store(StoreAdvisoryBoardModeratorRequest $request, AdvisoryBoard $item, AdvisoryBoardModerator $moderator)
    {
        $validated = $request->validated();

        $oldModerators = $item->moderators->pluck('user_id')->toArray();

        $route = route('admin.advisory-boards.edit', ['item' => $item]) . '#moderator';

        DB::beginTransaction();
        try {
            $validated['advisory_board_id'] = $item->id;
            $fillable = $this->getFillableValidated($validated, $moderator);
            $moderator->fill($fillable);
            $moderator->save();

            $moderator->user->assignRole(CustomRole::MODERATOR_ADVISORY_BOARD);

            DB::commit();

            if(!in_array($moderator->id, $oldModerators)) {
                $moderator->user->notify(new AdvBoardAssignedModerator($item));
            }

            return redirect($route)->with('success', trans_choice('custom.moderators', 1) . " " . __('messages.added_successfully_m'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect($route)->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Register new user and assign him as an advisory board moderator.
     *
     * @param StoreUserModeratorRequest $request
     * @param AdvisoryBoard             $item
     * @param AdvisoryBoardModerator    $moderator
     *
     * @return JsonResponse
     */
    public function ajaxRegister(Request $request, AdvisoryBoard $item, AdvisoryBoardModerator $moderator)
    {
        $req = new StoreUserModeratorRequest();
        $validator = Validator::make($request->all(), $req->rules());
        if($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 200);
        }
        $validated = $validator->validated();

        DB::beginTransaction();
        try {
            unset($validated['password_confirmation']);

            $validated['user_type'] = User::USER_TYPE_INTERNAL;

            $user = User::make($validated);
            $user->password = bcrypt($validated['password']);
            $user->email_verified_at = Carbon::now();
            $user->password_changed_at = Carbon::now();
            $user->save();

            $moderator->user_id = $user->id;
            $moderator->advisory_board_id = $item->id;
            $moderator->save();

            $user->assignRole(CustomRole::MODERATOR_ADVISORY_BOARD);

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
     * Remove the user from being moderator of the advisory board.
     *
     * @param AdvisoryBoard          $item
     * @param AdvisoryBoardModerator $moderator
     *
     * @return RedirectResponse
     */
    public function destroy(AdvisoryBoard $item, AdvisoryBoardModerator $moderator)
    {
        $this->authorize('update', $item);

        $route = route('admin.advisory-boards.edit', ['item' => $item]) . '#moderator';

        DB::beginTransaction();
        try {
            $moderator->delete();

            $moderate_advisory_boards = AdvisoryBoardModerator::where('user_id', $moderator->user_id)->where('advisory_board_id', $item->id)->get();

            // making sure it was the only advisory board
            if ($moderate_advisory_boards->count() === 0) {
                DB::table('model_has_roles')->where(['role_id' => 7, 'model_id' => $moderator->user_id])->delete();
            }

            DB::commit();

            return redirect($route)->with('success', trans_choice('custom.moderators', 1) . " " . __('messages.added_successfully_m'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect($route)->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }
}
