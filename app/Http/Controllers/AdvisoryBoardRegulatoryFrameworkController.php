<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardRegulatoryFramework;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Log;
use Validator;

class AdvisoryBoardRegulatoryFrameworkController extends AdminController
{

    /**
     * Store or update a newly created resource in storage.
     *
     * @param Request                          $request
     * @param AdvisoryBoard                    $item
     * @param AdvisoryBoardRegulatoryFramework $framework
     *
     * @return RedirectResponse
     */
    public function store(Request $request, AdvisoryBoard $item, AdvisoryBoardRegulatoryFramework $framework)
    {
        $this->authorize('update', $item);

        $route = route('admin.advisory-boards.edit', $item->id) . '#regulatory';

        $rules = [];
        foreach (config('available_languages') as $lang) {
            foreach (AdvisoryBoardRegulatoryFramework::translationFieldsProperties() as $field => $properties) {
                $rules[$field . '_' . $lang['code']] = $properties['rules'];
            }
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect($route)
                ->withInput()
                ->withErrors($validator);
        }

        $validated = $validator->validated();

        DB::beginTransaction();
        try {
            $validated['advisory_board_id'] = $item->id;

            foreach (config('available_languages') as $lang) {
                $validated['description_' . $lang['code']] = htmlspecialchars_decode($validated['framework_description_' . $lang['code']]);
                unset($validated['framework_description_' . $lang['code']]);
            }

            $fillable = $this->getFillableValidated($validated, $framework);
            $framework->fill($fillable);
            $framework->save();

            $this->storeTranslateOrNew(AdvisoryBoardRegulatoryFramework::TRANSLATABLE_FIELDS, $framework, $validated);

            DB::commit();

            return redirect($route)->with('success', trans_choice('custom.regulatory_framework', 1) . " " . __('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            Log::error($e);
            return redirect($route)->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }
}
