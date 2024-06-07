<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Enums\DocTypesEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardCustomRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardCustomRequest;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardCustom;
use App\Services\AdvisoryBoard\AdvisoryBoardFileService;
use App\Services\Notifications;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Log;
use Validator;

class AdvisoryBoardCustomController extends AdminController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param AdvisoryBoard                   $item
     * @param AdvisoryBoardCustom             $section
     *
     * @return JsonResponse
     */
    public function ajaxStore(Request $request, AdvisoryBoard $item, AdvisoryBoardCustom $section)
    {
        $req = new StoreAdvisoryBoardCustomRequest();
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $req->rules());
        if($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 200);
        }

        $validated = $validator->validated();
        DB::beginTransaction();
        try {
            $fillable = $this->getFillableValidated($validated, $section);

            $fillable['advisory_board_id'] = $item->id;
            $changes = $this->translateChanges(AdvisoryBoardCustom::TRANSLATABLE_FIELDS, $section, $validated);//use it to send detail changes in notification

            $section->fill($fillable);
            $section->save();

            $this->storeTranslateOrNew(AdvisoryBoardCustom::TRANSLATABLE_FIELDS, $section, $validated);
            $defaultLang = config('app.default_lang');
            $file_service = app(AdvisoryBoardFileService::class);
            $defaultFiles = $validated['file_' . $defaultLang] ?? [];
            foreach ($defaultFiles as $dKey => $dFile) {
                $file_service->upload(
                    $dFile,
                    $defaultLang,
                    $section->id,
                    $item->id,
                    DocTypesEnum::AB_CUSTOM_SECTION->value,
                    false,
                    $validated['file_description_' . $defaultLang][$dKey] ?? null,
                    $validated['file_name_' . $defaultLang][$dKey] ?? null
                );

                foreach (config('available_languages') as $lang) {
                    if($lang['code'] != $defaultLang) {
                        $files = $validated['file_' . $lang['code']] ?? [];
                        $lFile = sizeof($files) && isset($files[$dKey]) ? $files[$dKey] : $defaultFiles[$dKey];
                        $lDescription = sizeof($validated['file_description_' . $lang['code']]) && isset($validated['file_description_' . $lang['code']][$dKey]) ? $validated['file_description_' . $lang['code']][$dKey] : $validated['file_description_' . $defaultLang][$dKey];
                        $lname = sizeof($validated['file_name_' . $lang['code']]) && isset($validated['file_name_' . $lang['code']][$dKey]) ? $validated['file_name_' . $lang['code']][$dKey] : $validated['file_name_' . $defaultLang][$dKey];
                        $file_service->upload(
                            $lFile,
                            $lang['code'],
                            $section->id,
                            $item->id,
                            DocTypesEnum::AB_CUSTOM_SECTION->value,
                            false,
                            $lDescription,
                            $lname,
                        );
                    }
                }

            }

            DB::commit();

            //alert adb board modeRATOR
            if(sizeof($changes)){
                $notifyService = new Notifications();
                $notifyService->advChanges($item, request()->user(), 'Други - '.$section->title, $changes);
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
     * @param AdvisoryBoard       $item
     * @param AdvisoryBoardCustom $section
     *
     * @return JsonResponse
     */
    public function ajaxEdit(AdvisoryBoard $item, AdvisoryBoardCustom $section)
    {
        return response()->json($section);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdvisoryBoardCustomRequest $request
     * @param AdvisoryBoard                    $item
     *
     * @return JsonResponse
     */
    public function ajaxUpdate(UpdateAdvisoryBoardCustomRequest $request, AdvisoryBoard $item)
    {
        $req = new UpdateAdvisoryBoardCustomRequest();
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $req->rules());
        if($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 200);
        }

        $validated = $validator->validated();

        DB::beginTransaction();
        try {
            $section = AdvisoryBoardCustom::find($validated['section_id']);

            $fillable = $this->getFillableValidated($validated, $section);
            $changes = $this->translateChanges(AdvisoryBoardCustom::TRANSLATABLE_FIELDS, $section, $validated);//use it to send detail changes in notification

            $section->fill($fillable);
            $section->save();

            $this->storeTranslateOrNew(AdvisoryBoardCustom::TRANSLATABLE_FIELDS, $section, $validated);

            DB::commit();

            //alert adb board modeRATOR
            if(sizeof($changes)){
                $notifyService = new Notifications();
                $notifyService->advChanges($item, request()->user(), 'Други - '.$section->title, $changes);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['status' => 'error'], 500);
        }
    }

    public function order(Request $request, AdvisoryBoard $item)
    {
        $this->authorize('update', $item);

        $route = route('admin.advisory-boards.edit', $item->id) . '#custom';

        $rules = ['order' => 'required|json'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect($route)
                ->withInput()
                ->withErrors($validator);
        }

        $validated = $validator->validated();

        try {
            $order = json_decode($validated['order']);

            foreach ($order as $position => $id) {
                AdvisoryBoardCustom::where(['id' => $id, 'advisory_board_id' => $item->id])->update(['order' => $position + 1]);
            }

            return redirect($route)->with('success', __('messages.sections_order_successfully'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect($route)->withInput(request()->all())->with('danger', __('messages.sections_order_failed'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AdvisoryBoard       $item
     * @param AdvisoryBoardCustom $section
     *
     * @return RedirectResponse
     */
    public function destroy(AdvisoryBoard $item, AdvisoryBoardCustom $section)
    {
        $this->authorize('delete', [AdvisoryBoard::class, $item]);

        $route = route('admin.advisory-boards.edit', $item->id) . '#custom';

        try {
            $section->delete();

            return redirect($route)
                ->with('success', trans_choice('custom.section', 1) . ' ' . __('messages.deleted_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->to($route)->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param AdvisoryBoard       $item
     * @param AdvisoryBoardCustom $section
     *
     * @return RedirectResponse
     */
    public function restore(AdvisoryBoard $item, AdvisoryBoardCustom $section)
    {
        $this->authorize('restore', [AdvisoryBoard::class, $item]);

        $route = route('admin.advisory-boards.edit', $item->id) . '#custom';

        try {
            $section->restore();

            return redirect($route)
                ->with('success', trans_choice('custom.section', 1) . ' ' . __('messages.restored_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->to($route)->with('danger', __('messages.system_error'));
        }
    }
}
