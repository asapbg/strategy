<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardFunctionRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardFunctionRequest;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardFunction;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;
use Log;

class AdvisoryBoardFunctionController extends AdminController
{

    /**
     * Store or update sections.
     *
     * @param StoreAdvisoryBoardFunctionRequest $request
     * @param AdvisoryBoard                     $item
     *
     * @return JsonResponse
     */
    public function ajaxStore(StoreAdvisoryBoardFunctionRequest $request, AdvisoryBoard $item): JsonResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $new = new AdvisoryBoardFunction();
            $fillable = $this->getFillableValidated($validated, $new);
            $fillable['advisory_board_id'] = $item->id;
            $fillable['working_year'] = Carbon::create($fillable['working_year']);
            $new->fill($fillable);
            $new->save();

            $this->storeTranslateOrNew(AdvisoryBoardFunction::TRANSLATABLE_FIELDS, $new, $validated);

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['status' => 'error'], 500);
        }
    }

    public function ajaxEdit(AdvisoryBoard $item, AdvisoryBoardFunction $working_program)
    {
        $this->authorize('update', $item);

        return response()->json($working_program);
    }

    public function ajaxUpdate(UpdateAdvisoryBoardFunctionRequest $request, AdvisoryBoard $item)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $working_program = AdvisoryBoardFunction::find($validated['function_id']);
            $fillable = $this->getFillableValidated($validated, $working_program);
            $fillable['working_year'] = Carbon::create($fillable['working_year']);
            $working_program->fill($fillable);
            $working_program->save();

            $this->storeTranslateOrNew(AdvisoryBoardFunction::TRANSLATABLE_FIELDS, $working_program, $validated);

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['status' => 'error'], 500);
        }
    }
}
