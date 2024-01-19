<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardSecretariat;
use App\Services\Notifications;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

class AdvisoryBoardSecretariatController extends AdminController
{

    /**
     * Store or update a newly created resource in storage.
     *
     * @param Request $request
     * @param AdvisoryBoard            $item
     * @param AdvisoryBoardSecretariat $secretariat
     *
     * @return RedirectResponse
     */
    public function store(Request $request, AdvisoryBoard $item, AdvisoryBoardSecretariat $secretariat)
    {
        $this->authorize('update', $item);

        $route = route('admin.advisory-boards.edit', $item->id) . '#secretariat';

        $rules = [];
        foreach (config('available_languages') as $lang) {
            foreach (AdvisoryBoardSecretariat::translationFieldsProperties() as $field => $properties) {
                $rules[$field . '_' . $lang['code']] = $properties['rules'];
            }
        }
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return redirect($route)
                ->withInput()
                ->withErrors($validator);
        }

        $validated = $validator->validated();

        DB::beginTransaction();
        try {
            $validated['advisory_board_id'] = $item->id;
            $fillable = $this->getFillableValidated($validated, $secretariat);
            $secretariat->fill($fillable);
            $secretariat->save();

            foreach (config('available_languages') as $lang) {
                $validated['description_' . $lang['code']] = htmlspecialchars_decode($validated['description_' . $lang['code']]);
            }

            $this->storeTranslateOrNew(AdvisoryBoardSecretariat::TRANSLATABLE_FIELDS, $secretariat, $validated);

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
}
