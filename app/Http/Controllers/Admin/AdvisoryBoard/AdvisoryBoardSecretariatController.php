<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardSecretariatRequest;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardSecretariat;
use App\Services\Notifications;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

class AdvisoryBoardSecretariatController extends AdminController
{

    /**
     * Store or update a newly created resource in storage.
     *
     * @param StoreAdvisoryBoardSecretariatRequest $request
     * @param AdvisoryBoard            $item
     * @param AdvisoryBoardSecretariat $secretariat
     *
     * @return RedirectResponse
     */
    public function store(StoreAdvisoryBoardSecretariatRequest $request, AdvisoryBoard $item, AdvisoryBoardSecretariat $secretariat)
    {
        if( ($item->id && $request->user()->cannot('update', $item)) || (!$item->id && $request->user()->cannot('create', AdvisoryBoard::class)) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        $route = route('admin.advisory-boards.edit', $item->id) . '#secretariat';

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $changes = $this->translateChanges(AdvisoryBoardSecretariat::TRANSLATABLE_FIELDS, $secretariat, $validated);//use it to send detail changes in notification

            $validated['advisory_board_id'] = $item->id;
            $fillable = $this->getFillableValidated($validated, $secretariat);
            $secretariat->fill($fillable);
            $secretariat->save();
            $this->storeTranslateOrNew(AdvisoryBoardSecretariat::TRANSLATABLE_FIELDS, $secretariat, $validated);

            DB::commit();

            //alert adb board modeRATOR
            if(sizeof($changes)){
                $notifyService = new Notifications();
                $notifyService->advChanges($item, request()->user(), __('custom.secretariat'), $changes);
            }

            return redirect($route)->with('success', trans_choice('custom.section', 1) . " " . __('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect($route)->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }
}
