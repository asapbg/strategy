<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardSecretaryCouncilRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardSecretaryCouncilRequest;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardSecretaryCouncil;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Log;

class AdvisoryBoardSecretaryCouncilController extends AdminController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdvisoryBoardSecretaryCouncilRequest $request
     *
     * @return JsonResponse
     */
    public function ajaxStore(StoreAdvisoryBoardSecretaryCouncilRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $item = new AdvisoryBoardSecretaryCouncil();
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();

            $validated['advisory_board_secretary_council_id'] = $item->id;

            $this->storeTranslateOrNew(AdvisoryBoardSecretaryCouncil::TRANSLATABLE_FIELDS, $item, $validated);

            DB::commit();

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
     * @param AdvisoryBoard                 $item
     * @param AdvisoryBoardSecretaryCouncil $secretary
     *
     * @return JsonResponse
     */
    public function ajaxEdit(AdvisoryBoard $item, AdvisoryBoardSecretaryCouncil $secretary)
    {
        $this->authorize('view', $secretary);

        return response()->json($secretary);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdvisoryBoardSecretaryCouncilRequest $request
     * @param AdvisoryBoard                              $item
     *
     * @return JsonResponse
     */
    public function ajaxUpdate(UpdateAdvisoryBoardSecretaryCouncilRequest $request, AdvisoryBoard $item)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $secretary = AdvisoryBoardSecretaryCouncil::find($validated['advisory_board_secretary_council_id']);

            $this->storeTranslateOrNew(AdvisoryBoardSecretaryCouncil::TRANSLATABLE_FIELDS, $secretary, $validated);

            DB::commit();

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
     * @param AdvisoryBoard                 $item
     * @param AdvisoryBoardSecretaryCouncil $secretary
     *
     * @return RedirectResponse
     */
    public function destroy(AdvisoryBoard $item, AdvisoryBoardSecretaryCouncil $secretary)
    {
        $route = route('admin.advisory-boards.edit', $secretary->advisory_board_id) . '#secretary-of-council';

        try {
            $secretary->delete();

            return redirect($route)
                ->with('success', trans_choice('custom.secretary', 1) . " $secretary->name " . __('messages.deleted_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->to($route)->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Restore the specified resource.
     *
     * @param AdvisoryBoard                 $item
     * @param AdvisoryBoardSecretaryCouncil $secretary
     *
     * @return RedirectResponse
     */
    public function restore(AdvisoryBoard $item, AdvisoryBoardSecretaryCouncil $secretary)
    {
        $route = route('admin.advisory-boards.edit', $secretary->advisory_board_id) . '#secretary-of-council';

        try {
            $secretary->restore();

            return redirect($route)
                ->with('success', trans_choice('custom.secretary', 1) . " $secretary->name " . __('messages.restored_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->to($route)->with('danger', __('messages.system_error'));
        }
    }
}
