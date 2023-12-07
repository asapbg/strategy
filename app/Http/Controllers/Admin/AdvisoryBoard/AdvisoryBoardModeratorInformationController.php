<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardModeratorInformation;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Log;

class AdvisoryBoardModeratorInformationController extends AdminController
{

    /**
     * Store or update a newly created resource in storage.
     *
     * @param AdvisoryBoard                     $item
     * @param AdvisoryBoardModeratorInformation $information
     *
     * @return RedirectResponse
     */
    public function store(AdvisoryBoard $item, AdvisoryBoardModeratorInformation $information)
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
}
