<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StoreEstablishmentRequest;
use App\Http\Requests\StoreOrganizationRulesRequest;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardEstablishment;
use App\Models\AdvisoryBoardOrganizationRule;
use App\Services\Notifications;
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

        $req = new StoreOrganizationRulesRequest();
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $req->rules());
        if($validator->fails()) {
            return back()->withFragment('regulatory')->withInput()->withErrors($validator->errors())->with('organization', 1);
        }

        $validated = $validator->validated();

        $this->authorize('update', $item);

        $route = route('admin.advisory-boards.edit', $item->id) . '#regulatory';

//        $rules = [];
//        foreach (config('available_languages') as $lang) {
//            foreach (AdvisoryBoardOrganizationRule::translationFieldsProperties() as $field => $properties) {
//                $rules[$field . '_' . $lang['code']] = $properties['rules'];
//            }
//        }
//        $validator = Validator::make($request->all(), $rules);
//
//        if ($validator->fails()) {
//            return redirect($route)
//                ->withInput()
//                ->withErrors($validator);
//        }
//
//        $validated = $validator->validated();

        DB::beginTransaction();
        try {
            $validated['advisory_board_id'] = $item->id;
            $changes = $this->translateChanges(AdvisoryBoardOrganizationRule::TRANSLATABLE_FIELDS, $rule, $validated);//use it to send detail changes in notification

            foreach (config('available_languages') as $lang) {
                if(isset($validated['description_' . $lang['code']])) {
                    $validated['description_' . $lang['code']] = htmlspecialchars_decode($validated['description_' . $lang['code']]);
                }
            }

            $fillable = $this->getFillableValidated($validated, $rule);
            $rule->fill($fillable);
            $rule->save();

            $this->storeTranslateOrNew(AdvisoryBoardOrganizationRule::TRANSLATABLE_FIELDS, $rule, $validated);

            DB::commit();

            //alert adb board modeRATOR
            if(sizeof($changes)){
                $notifyService = new Notifications();
                $notifyService->advChanges($item, request()->user(), __('custom.advisory_board_regulatory_framework'), $changes);
            }

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

        $req = new StoreEstablishmentRequest();
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $req->rules());
        if($validator->fails()) {
            return back()->withFragment('regulatory')->withInput()->withErrors($validator->errors())->with('establishment', 1);
        }
        $validated = $validator->validated();

        $route = route('admin.advisory-boards.edit', $item->id) . '#regulatory';

//        $rules = [];
//        foreach (config('available_languages') as $lang) {
//            foreach (AdvisoryBoardEstablishment::translationFieldsProperties() as $field => $properties) {
//                $rules[$field . '_' . $lang['code']] = $properties['rules'];
//            }
//        }
//        $validator = Validator::make($request->all(), $rules);

//        if ($validator->fails()) {
//            return redirect($route)
//                ->withInput()
//                ->withErrors($validator);
//        }

//        $validated = $validator->validated();

        DB::beginTransaction();
        try {
            $validated['advisory_board_id'] = $item->id;
            $changes = $this->translateChanges(AdvisoryBoardOrganizationRule::TRANSLATABLE_FIELDS, $establishment, $validated);//use it to send detail changes in notification

            foreach (config('available_languages') as $lang) {
                if(isset($validated['description_' . $lang['code']])) {
                    $validated['description_' . $lang['code']] = htmlspecialchars_decode($validated['description_' . $lang['code']]);
                }
            }

            $fillable = $this->getFillableValidated($validated, $establishment);
            $establishment->fill($fillable);
            $establishment->save();

            $this->storeTranslateOrNew(AdvisoryBoardEstablishment::TRANSLATABLE_FIELDS, $establishment, $validated);

            DB::commit();

            //alert adb board modeRATOR
            if(sizeof($changes)){
                $notifyService = new Notifications();
                $notifyService->advChanges($item, request()->user(), __('custom.advisory_board_establishments'), $changes);
            }

            return redirect($route)->with('success', __('validation.attributes.act_of_creation') . " " . __('messages.updated_successfully_m'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect($route)->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }
}
