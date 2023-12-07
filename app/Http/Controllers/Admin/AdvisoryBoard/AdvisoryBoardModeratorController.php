<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StoreAdvisoryBoardModeratorRequest;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardModerator;
use App\Models\AdvisoryBoardModeratorInformation;
use App\Models\CustomRole;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Log;

class AdvisoryBoardModeratorController extends AdminController
{

    /**
     * Store or update global moderator's information.
     *
     * @param AdvisoryBoard                     $item
     * @param AdvisoryBoardModeratorInformation $information
     *
     * @return RedirectResponse
     */
    public function storeInformation(AdvisoryBoard $item, AdvisoryBoardModeratorInformation $information)
    {
        $this->authorize('update', $item);

        $route = route('admin.advisory-boards.edit', $item->id) . '#moderator';

        $rules = [];
        foreach (config('available_languages') as $lang) {
            foreach (AdvisoryBoardModeratorInformation::translationFieldsProperties() as $field => $properties) {
                $rules[$field . '_' . $lang['code']] = $properties['rules'];
            }
        }

        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return redirect($route)
                ->withInput()
                ->withErrors($validator);
        }

        $validated = $validator->validated();

        DB::beginTransaction();
        try {
            $validated['advisory_board_id'] = $item->id;
            $fillable = $this->getFillableValidated($validated, $information);
            $information->fill($fillable);
            $information->save();

            foreach (config('available_languages') as $lang) {
                $validated['description_' . $lang['code']] = htmlspecialchars_decode($validated['description_' . $lang['code']]);
            }

            $this->storeTranslateOrNew(AdvisoryBoardModeratorInformation::TRANSLATABLE_FIELDS, $information, $validated);

            DB::commit();

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

        $route = route('admin.advisory-boards.edit', ['item' => $item]) . '#moderators';

        DB::beginTransaction();
        try {
            $validated['advisory_board_id'] = $item->id;
            $fillable = $this->getFillableValidated($validated, $moderator);
            $moderator->fill($fillable);
            $moderator->save();

            $moderator->user->assignRole(CustomRole::MODERATOR_ADVISORY_BOARD);

            DB::commit();

            return redirect($route)->with('success', trans_choice('custom.moderators', 1) . " " . __('messages.added_successfully_m'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect($route)->withInput(request()->all())->with('danger', __('messages.system_error'));
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

        $route = route('admin.advisory-boards.edit', ['item' => $item]) . '#moderators';

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
