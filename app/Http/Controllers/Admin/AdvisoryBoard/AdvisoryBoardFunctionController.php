<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Enums\StatusEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardFunctionRequest;
use App\Models\AdvisoryBoardFunction;
use Carbon\Carbon;
use DB;
use Illuminate\Http\RedirectResponse;
use Log;

class AdvisoryBoardFunctionController extends AdminController
{

    /**
     * Store or update sections.
     *
     * @param StoreAdvisoryBoardFunctionRequest $request
     *
     * @return RedirectResponse
     */
    public function store(StoreAdvisoryBoardFunctionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $advisory_board_id = $request->route()->parameters['item'];
        $route = route('admin.advisory-boards.edit', $advisory_board_id) . '#functions';

        DB::beginTransaction();
        try {
            $item = AdvisoryBoardFunction::where('advisory_board_id', $advisory_board_id)->where('status', StatusEnum::ACTIVE->value)->first() ?? new AdvisoryBoardFunction();

            if (!Carbon::parse($item->created_at)->isSameYear(Carbon::now())) {
                $item->status = StatusEnum::INACTIVE->value;
                $item->save();
                $item = new AdvisoryBoardFunction();
            }

            $message = $item->id ? __('messages.updated_successfully_f') : __('messages.created_successfully_f');
            $fillable = $this->getFillableValidated($validated, $item);
            $fillable['advisory_board_id'] = $advisory_board_id;
            $item->fill($fillable);
            $item->save();

            foreach (config('available_languages') as $lang) {
                $validated['description_' . $lang['code']] = htmlspecialchars_decode($validated['description_' . $lang['code']]);
            }

            $this->storeTranslateOrNew(AdvisoryBoardFunction::TRANSLATABLE_FIELDS, $item, $validated);

            DB::commit();

            return redirect($route)->with('success', trans_choice('custom.section', 1) . " " . $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect($route)->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }
}
