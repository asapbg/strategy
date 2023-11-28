<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DocTypesEnum;
use App\Http\Requests\StoreAdvisoryBoardFunctionFileRequest;
use App\Http\Requests\UpdateAdvisoryBoardFunctionFileRequest;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardFunctionFile;
use App\Models\File;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Log;

class AdvisoryBoardFunctionFileController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdvisoryBoardFunctionFileRequest $request
     *
     * @return JsonResponse
     */
    public function ajaxStore(StoreAdvisoryBoardFunctionFileRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = AdvisoryBoard::find($request->route()->parameters['item']);

        DB::beginTransaction();
        try {
            foreach (config('available_languages') as $lang) {
                //Add file and attach
                $this->uploadFile(
                    $model,
                    $validated['file_' . $lang['code']],
                    File::CODE_AB_FUNCTION,
                    DocTypesEnum::AB_FUNCTION,
                    $validated['file_description_' . $lang['code']],
                    $lang['code'],
                    $validated['file_name_' . $lang['code']]
                );
            }

            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['status' => 'error', 'message' => __('messages.system_error')], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AdvisoryBoardFunctionFile $advisoryBoardFunctionFile
     *
     * @return \Illuminate\Http\Response
     */
    public function show(AdvisoryBoardFunctionFile $advisoryBoardFunctionFile)
    {
        //
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
        return response()->json($file);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdvisoryBoardFunctionFileRequest $request
     *
     * @return JsonResponse
     */
    public function ajaxUpdate(UpdateAdvisoryBoardFunctionFileRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $model = AdvisoryBoard::find($request->route()->parameters['item']);

        DB::beginTransaction();
        try {
            $file = File::find($validated['file_id']);

            //Add file and attach
            $updated_file = $this->uploadFile(
                $model,
                $validated['file'],
                File::CODE_AB_FUNCTION,
                DocTypesEnum::AB_FUNCTION,
                $validated['file_description_' . $file->locale],
                $file->locale,
                $validated['file_name_' . $file->locale]
            );

            if (\Illuminate\Support\Facades\File::exists(public_path('files' . DIRECTORY_SEPARATOR . $updated_file->path))) {
                $file->delete();
            }

            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
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
        $route = route('admin.advisory-boards.edit', $item) . '#functions';

        try {
            $file_name = $file->custom_name ?? $file->filename;

            $file->delete();

            return redirect()->to($route)
                ->with('success', __('custom.file') . " $file_name " . __('messages.deleted_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect($route)->with('danger', __('messages.system_error'));
        }
    }
}
