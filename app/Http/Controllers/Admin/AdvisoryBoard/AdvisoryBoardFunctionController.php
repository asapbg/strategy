<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardFunctionRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardFunctionRequest;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardFunction;
use App\Services\Notifications;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

class AdvisoryBoardFunctionController extends AdminController
{

    /**
     * Store or update sections.
     *
     * @param Request $request
     * @param AdvisoryBoard                     $item
     *
     * @return JsonResponse
     */
    public function ajaxStore(Request $request, AdvisoryBoard $item): JsonResponse
    {
        $req = new StoreAdvisoryBoardFunctionRequest();
        $validator = Validator::make($request->all(), $req->rules());
        if($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 200);
        }

        $validated = $validator->validated();

        DB::beginTransaction();
        try {
            $new = new AdvisoryBoardFunction();
            $fillable = $this->getFillableValidated($validated, $new);
            $fillable['advisory_board_id'] = $item->id;
            $fillable['working_year'] = Carbon::create($fillable['working_year']);

            $changes = $this->mainChanges(AdvisoryBoardFunction::CHANGEABLE_FIELDS, $new, $validated);
            $new->fill($fillable);
            $changes = array_merge($this->translateChanges(AdvisoryBoardFunction::TRANSLATABLE_FIELDS, $new, $validated), $changes);//use it to send detail changes in notification
            $new->save();

            $this->storeTranslateOrNew(AdvisoryBoardFunction::TRANSLATABLE_FIELDS, $new, $validated);
            DB::commit();

            //alert adb board modeRATOR
            if(sizeof($changes)){
                $notifyService = new Notifications();
                $notifyService->advChanges($item, request()->user(), 'Работни програми', $changes);
            }

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

    public function ajaxUpdate(Request $request, AdvisoryBoard $item)
    {
        $req = new UpdateAdvisoryBoardFunctionRequest();
        $validator = Validator::make($request->all(), $req->rules());
        if($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 200);
        }
        $validated = $validator->validated();

        $this->authorize('update', $item);
        DB::beginTransaction();
        try {
            $working_program = AdvisoryBoardFunction::find($validated['function_id']);
            $fillable = $this->getFillableValidated($validated, $working_program);
            $fillable['working_year'] = Carbon::create($fillable['working_year']);

            $changes = $this->mainChanges(AdvisoryBoardFunction::CHANGEABLE_FIELDS, $working_program, $validated);
            $working_program->fill($fillable);
            $changes = array_merge($this->translateChanges(AdvisoryBoardFunction::TRANSLATABLE_FIELDS, $working_program, $validated), $changes);//use it to send detail changes in notification

            $working_program->save();

            $this->storeTranslateOrNew(AdvisoryBoardFunction::TRANSLATABLE_FIELDS, $working_program, $validated);

            DB::commit();

            //alert adb board modeRATOR
            if(sizeof($changes)){
                $notifyService = new Notifications();
                $notifyService->advChanges($item, request()->user(), 'Работни програми', $changes);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AdvisoryBoard         $item
     * @param AdvisoryBoardFunction $working_program
     *
     * @return RedirectResponse
     */
    public function destroy(AdvisoryBoard $item, AdvisoryBoardFunction $working_program)
    {
        $this->authorize('delete', $item);

        $route = route('admin.advisory-boards.edit', $item->id) . '#functions';

        try {
            $working_program->delete();

            return redirect($route)
                ->with('success', trans_choice('custom.function', 1) . ' ' . __('messages.deleted_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->to($route)->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param AdvisoryBoard         $item
     * @param AdvisoryBoardFunction $working_program
     *
     * @return RedirectResponse
     */
    public function restore(AdvisoryBoard $item, AdvisoryBoardFunction $working_program)
    {
        $this->authorize('restore', $item);

        $route = route('admin.advisory-boards.edit', $item->id) . '#functions';

        try {
            $working_program->restore();

            return redirect($route)
                ->with('success', trans_choice('custom.function', 1) . ' ' . __('messages.restored_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->to($route)->with('danger', __('messages.system_error'));
        }
    }
}
