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
     * @param StoreAdvisoryBoardCustomRequest $request
     * @param AdvisoryBoard                   $item
     * @param AdvisoryBoardCustom             $section
     *
     * @return JsonResponse
     */
    public function ajaxStore(StoreAdvisoryBoardCustomRequest $request, AdvisoryBoard $item, AdvisoryBoardCustom $section)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $fillable = $this->getFillableValidated($validated, $section);

            $fillable['advisory_board_id'] = $item->id;

            $section->fill($fillable);
            $section->save();

            $validated['advisory_board_custom_id'] = $section->id;

            foreach (config('available_languages') as $lang) {
                $validated['body_' . $lang['code']] = htmlspecialchars_decode($validated['body_' . $lang['code']]);
            }

            $this->storeTranslateOrNew(AdvisoryBoardCustom::TRANSLATABLE_FIELDS, $section, $validated);

            foreach (config('available_languages') as $language) {
                if (isset($validated['file_' . $language['code']])) {
                    foreach ($validated['file_' . $language['code']] as $key => $file) {
                        $name_key = 'file_name_' . $language['code'];
                        $description_key = 'file_description_' . $language['code'];

                        $file_service = app(AdvisoryBoardFileService::class);
                        $file_service->upload(
                            $file,
                            $language['code'],
                            $section->id,
                            $item->id,
                            DocTypesEnum::AB_CUSTOM_SECTION->value,
                            false,
                            $validated[$description_key][$key] ?? '',
                            $validated[$name_key][$key] ?? null
                        );
                    }
                }
            }

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
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $section = AdvisoryBoardCustom::find($validated['section_id']);

            $fillable = $this->getFillableValidated($validated, $section);
            $section->fill($fillable);
            $section->save();

            $this->storeTranslateOrNew(AdvisoryBoardCustom::TRANSLATABLE_FIELDS, $section, $validated);

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

            //alert adb board modeRATOR
            $notifyService = new Notifications();
            $notifyService->advChanges($item, request()->user());

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
