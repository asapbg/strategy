<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardFileRequest;
use App\Http\Requests\UpdateAdvisoryBoardFunctionFileRequest;
use App\Models\AdvisoryBoard;
use App\Models\File;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Log;

class AdvisoryBoardFileController extends AdminController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdvisoryBoardFileRequest $request
     * @param AdvisoryBoard                 $item
     *
     * @return JsonResponse
     */
    public function ajaxStore(StoreAdvisoryBoardFileRequest $request, AdvisoryBoard $item): JsonResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            foreach (config('available_languages') as $lang) {
                $this->uploadFile(
                    $item,
                    $validated['file_' . $lang['code']],
                    File::CODE_AB_FUNCTION,
                    $validated['doc_type_id'],
                    $validated['file_description_' . $lang['code']],
                    $lang['code'],
                    $validated['file_name_' . $lang['code']]
                );
            }

            DB::commit();
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
     * @param UpdateAdvisoryBoardFunctionFileRequest $request
     * @param AdvisoryBoard                          $item
     *
     * @return JsonResponse
     */
    public function ajaxUpdate(UpdateAdvisoryBoardFunctionFileRequest $request, AdvisoryBoard $item): JsonResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $file = File::find($validated['file_id']);

            //Add file and attach
            $updated_file = $this->uploadFile(
                $item,
                $validated['file'],
                File::CODE_AB_FUNCTION,
                $file->doc_type,
                $validated['file_description_' . $file->locale],
                $file->locale,
                $validated['file_name_' . $file->locale]
            );

            if (\Illuminate\Support\Facades\File::exists(public_path('files' . DIRECTORY_SEPARATOR . $updated_file->path))) {
                $file->delete();
            }

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
     *
     * @return RedirectResponse
     */
    public function destroy(AdvisoryBoard $item, File $file)
    {
        $this->authorize('delete', [AdvisoryBoard::class, $item]);

        $route = redirect()->back()->getTargetUrl() . $file->advisoryBoardTab;

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
     *
     * @return RedirectResponse
     */
    public function restore(AdvisoryBoard $item, File $file)
    {
        $this->authorize('restore', [AdvisoryBoard::class, $item]);

        $route = redirect()->back()->getTargetUrl() . $file->advisoryBoardTab;

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
