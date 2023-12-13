<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardEstablishment;
use App\Models\AdvisoryBoardOrganizationRule;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Log;
use Validator;

class AdvisoryBoardRegulatoryFrameworkController extends AdminController
{

    /**
     * Store or update a newly created resource in storage.
     *
     * @param Request                       $request
     * @param AdvisoryBoard                 $item
     * @param AdvisoryBoardOrganizationRule $rule
     *
     * @return RedirectResponse
     */
    public function storeOrganizationRules(Request $request, AdvisoryBoard $item, AdvisoryBoardOrganizationRule $rule)
    {
        $this->authorize('update', $item);

        $route = route('admin.advisory-boards.edit', $item->id) . '#regulatory';

        $rules = [];
        foreach (config('available_languages') as $lang) {
            foreach (AdvisoryBoardOrganizationRule::translationFieldsProperties() as $field => $properties) {
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
                $validated['description_' . $lang['code']] = htmlspecialchars_decode($validated['rules_description_' . $lang['code']]);
                unset($validated['rules_description_' . $lang['code']]);
            }

            $fillable = $this->getFillableValidated($validated, $rule);
            $rule->fill($fillable);
            $rule->save();

            $this->storeTranslateOrNew(AdvisoryBoardOrganizationRule::TRANSLATABLE_FIELDS, $rule, $validated);

            DB::commit();

            return redirect($route)->with('success', trans_choice('custom.regulatory_framework', 1) . " " . __('messages.updated_successfully_f'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect($route)->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Store or update a newly created resource in storage.
     *
     * @param Request                    $request
     * @param AdvisoryBoard              $item
     * @param AdvisoryBoardEstablishment $establishment
     *
     * @return RedirectResponse
     */
    public function storeEstablishment(Request $request, AdvisoryBoard $item, AdvisoryBoardEstablishment $establishment)
    {
        $this->authorize('update', $item);

        $route = route('admin.advisory-boards.edit', $item->id) . '#regulatory';

        $rules = [];
        foreach (config('available_languages') as $lang) {
            foreach (AdvisoryBoardEstablishment::translationFieldsProperties() as $field => $properties) {
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
                $validated['description_' . $lang['code']] = htmlspecialchars_decode($validated['establishment_description_' . $lang['code']]);
                unset($validated['establishment_description_' . $lang['code']]);
            }

            $fillable = $this->getFillableValidated($validated, $establishment);
            $establishment->fill($fillable);
            $establishment->save();

            $this->storeTranslateOrNew(AdvisoryBoardOrganizationRule::TRANSLATABLE_FIELDS, $establishment, $validated);

            DB::commit();

            return redirect($route)->with('success', trans_choice('custom.regulatory_framework', 1) . " " . __('messages.updated_successfully_f'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect($route)->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }
}
