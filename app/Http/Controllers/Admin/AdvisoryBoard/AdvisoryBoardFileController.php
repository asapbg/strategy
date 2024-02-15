<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Enums\DocTypesEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardFileRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardFileRequest;
use App\Models\AdvisoryBoard;
use App\Models\File;
use App\Services\AdvisoryBoard\AdvisoryBoardFileService;
use App\Services\Notifications;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

class AdvisoryBoardFileController extends AdminController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdvisoryBoardFileRequest $request
     * @param AdvisoryBoard                 $item
     * @param AdvisoryBoardFileService      $file_service
     *
     * @return JsonResponse
     */
    public function ajaxStore(Request $request, AdvisoryBoard $item, AdvisoryBoardFileService $file_service): JsonResponse
    {
        $req = new StoreAdvisoryBoardFileRequest();
        $validator = Validator::make($request->all(), $req->rules());
        if($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 200);
        }

        $validated = $validator->validated();

        DB::beginTransaction();
        try {
            $defaultLang = config('app.default_lang');
            foreach (config('available_languages') as $lang) {
                $isMainLang = $defaultLang == $lang['code'];
                $langCode = $lang['code'];
                $file_service->upload(
                    empty($validated['file_' . $lang['code']]) ? $validated['file_' . $defaultLang] : $validated['file_' . $lang['code']],
                    $lang['code'],
                    $validated['object_id'],
                    $item->id,
                    $validated['doc_type_id'],
                    false,
                    empty($validated['file_description_' . $lang['code']]) ? $validated['file_description_' . $defaultLang] : $validated['file_description_' . $lang['code']],
                    empty($validated['file_name_' . $langCode]) ? $validated['file_name_' . $defaultLang] : $validated['file_name_' . $langCode],
                    $validated['resolution_council_ministers'] ?? null,
                    $validated['state_newspaper'] ?? null,
                    $validated['effective_at'] ?? null,
                    null,
                    $validated['code_object'] ?? null
                );
            }

            DB::commit();
            //alert adb board modeRATOR
            $notifyService = new Notifications();
            $notifyService->advChanges($item, request()->user());

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['status' => 'error', 'message' => __('messages.system_error')], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AdvisoryBoard $item
     * @param File          $file
     *
     * @return JsonResponse
     */
    public function ajaxEdit(AdvisoryBoard $item, File $file): JsonResponse
    {
        $this->authorize('update', [AdvisoryBoard::class, $item]);

        return response()->json($file);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdvisoryBoardFileRequest $request
     * @param AdvisoryBoard                  $item
     * @param AdvisoryBoardFileService       $file_service
     *
     * @return JsonResponse
     */
    public function ajaxUpdate(Request $request, AdvisoryBoard $item, AdvisoryBoardFileService $file_service): JsonResponse
    {
//        $validated = $request->validated();

        $req = new UpdateAdvisoryBoardFileRequest();
        $validator = Validator::make($request->all(), $req->rules());
        if($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 200);
        }

        $validated = $validator->validated();

        DB::beginTransaction();
        try {
            if (!isset($validated['file'])) {
                $file = File::find($validated['file_id']);
                if (isset($validated['effective_at'])) {
                    $file->effective_at = isset($validated['effective_at']) && !empty($validated['effective_at']) ? Carbon::parse($validated['effective_at'])->format('Y-m-d H:i:s') : null;
                    $file->resolution_council_ministers = isset($validated['resolution_council_ministers']) && !empty($validated['resolution_council_ministers']) ? $validated['resolution_council_ministers'] : null;
                    $file->state_newspaper = isset($validated['state_newspaper']) && !empty($validated['state_newspaper']) ? $validated['state_newspaper'] : null;
                }

//                foreach (config('available_languages') as $lang) {
//                    if (isset($validated['file_name_' . $lang['code']])) {
                        $file->update(['custom_name' => $validated['file_name_' . $file->locale], 'description_'.$file->locale => $validated['file_description_' . $file->locale]]);
//                    }
//                }

                DB::commit();

                //alert adb board modeRATOR
                $notifyService = new Notifications();
                $notifyService->advChanges($item, request()->user());

                return response()->json(['status' => 'success']);
            }

            $file = File::find($validated['file_id']);

            //Add file and attach
            $file_service->upload(
                $validated['file'],
                $file->locale,
                $file->id_object,
                $item->id,
                $file->doc_type,
                true,
                $validated['file_description_' . $file->locale],
                $validated['file_name_' . $file->locale],
                $validated['resolution_council_ministers'] ?? null,
                $validated['state_newspaper'] ?? null,
                $validated['effective_at'] ?? null,
                $file->parent_id ?? $file->id
            );

//            if (\Illuminate\Support\Facades\File::exists(public_path('files' . DIRECTORY_SEPARATOR . $updated_file->path))) {
//                $file->delete();
//            }

            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['status' => 'error', 'message' => __('messages.system_error')], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AdvisoryBoard $item
     * @param File          $file
     * @param string|null          $tab
     *
     * @return RedirectResponse
     */
    public function destroy(AdvisoryBoard $item, File $file, $tab = null)
    {
        $this->authorize('delete', [AdvisoryBoard::class, $item]);

        $route = url()->previous() . (!empty($tab) ? '#'.$tab : $file->advisoryBoardTab);
        try {
            $file_name = $file->custom_name ?? $file->filename;

            $file->delete();

            return redirect()->to($route)
                ->with('success', __('custom.file') . " $file_name " . __('messages.deleted_successfully_m'));
        } catch (Exception $e) {
            Log::error($e);
            return redirect($route)->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param AdvisoryBoard $item
     * @param File          $file
     * @param string|null $tab
     *
     * @return RedirectResponse
     */
    public function restore(AdvisoryBoard $item, File $file, $tab = null)
    {
        $this->authorize('restore', [AdvisoryBoard::class, $item]);

        //$route = redirect()->back()->getTargetUrl() . !empty($tab) ? '#'.$tab : $file->advisoryBoardTab;
        $route = url()->previous() . (!empty($tab) ? '#'.$tab : $file->advisoryBoardTab);
        try {
            $file_name = $file->custom_name ?? $file->filename;

            $file->restore();

            return redirect()->to($route)
                ->with('success', __('custom.file') . " $file_name " . __('messages.restored_successfully_m'));
        } catch (Exception $e) {
            Log::error($e);
            return redirect($route)->with('danger', __('messages.system_error'));
        }
    }
}
