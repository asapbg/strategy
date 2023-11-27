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
    public function store(StoreAdvisoryBoardFunctionFileRequest $request): JsonResponse
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
     * @param \App\Models\AdvisoryBoardFunctionFile $advisoryBoardFunctionFile
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(AdvisoryBoardFunctionFile $advisoryBoardFunctionFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateAdvisoryBoardFunctionFileRequest $request
     * @param \App\Models\AdvisoryBoardFunctionFile                     $advisoryBoardFunctionFile
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdvisoryBoardFunctionFileRequest $request, AdvisoryBoardFunctionFile $advisoryBoardFunctionFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AdvisoryBoardFunctionFile $advisoryBoardFunctionFile
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvisoryBoardFunctionFile $advisoryBoardFunctionFile)
    {
        //
    }
}
