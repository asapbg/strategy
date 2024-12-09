<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard\Nomenclature;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardNomenclatureFieldOfActionRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardNomenclatureFieldOfActionRequest;
use App\Models\AdvisoryBoard\AdvisoryBoardNomenclatureFieldOfAction;
use App\Models\ModelActivityExtend;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdvisoryBoardNomenclatureFieldOfActionController extends AdminController
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $filter_active = $request->filled('active') ? $request->get('active') : 1;
        $filter_deleted = $request->offsetGet('deleted');
        $filter_name = $request->input('name');
        $filter = $this->filters($request);

        $paginate = $filter['paginate'] ?? ModelActivityExtend::PAGINATE;

        $actions = AdvisoryBoardNomenclatureFieldOfAction::orderByTranslation('name')
            ->where('active', $filter_active)
            ->when($filter_name, fn($q) => $q->whereHas('translations', fn($q) => $q->where('name', 'like', '%' . $filter_name . '%')))
            ->when(!empty($filter_deleted), fn($q) => $q->onlyTrashed())
            ->paginate($paginate);

        $toggleBooleanModel = explode('\\', AdvisoryBoardNomenclatureFieldOfAction::class);
        $toggleBooleanModel = $toggleBooleanModel[count($toggleBooleanModel) - 1];

        return $this->view('admin.advisory-boards.nomenclatures.field-of-actions.index', compact('actions', 'filter', 'toggleBooleanModel'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return $this->view('admin.advisory-boards.nomenclatures.field-of-actions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdvisoryBoardNomenclatureFieldOfActionRequest $request
     *
     * @return RedirectResponse
     */
    public function store(StoreAdvisoryBoardNomenclatureFieldOfActionRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $field_of_action = new AdvisoryBoardNomenclatureFieldOfAction();
            $fillable = $this->getFillableValidated($validated, $field_of_action);
            $field_of_action->fill($fillable);
            $field_of_action->save();

            $this->storeTranslateOrNew(AdvisoryBoardNomenclatureFieldOfAction::TRANSLATABLE_FIELDS, $field_of_action, $validated);

            DB::commit();
            return redirect(route('admin.advisory-boards.nomenclature.field-of-actions.index'))
                ->with('success', trans_choice('validation.attributes.field_of_action', 1) . " " . __('messages.created_successfully_f'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AdvisoryBoardNomenclatureFieldOfAction $item
     *
     * @return View
     */
    public function edit(AdvisoryBoardNomenclatureFieldOfAction $item): View
    {
        $translatableFields = AdvisoryBoardNomenclatureFieldOfAction::translationFieldsProperties();

        return $this->view('admin.advisory-boards.nomenclatures.field-of-actions.edit', compact('item', 'translatableFields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdvisoryBoardNomenclatureFieldOfActionRequest $request
     * @param AdvisoryBoardNomenclatureFieldOfAction              $action
     *
     * @return RedirectResponse
     */
    public function update(UpdateAdvisoryBoardNomenclatureFieldOfActionRequest $request, AdvisoryBoardNomenclatureFieldOfAction $action): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $fillable = $this->getFillableValidated($validated, $action);
            $action->fill($fillable);
            $action->save();
            $this->storeTranslateOrNew(AdvisoryBoardNomenclatureFieldOfAction::TRANSLATABLE_FIELDS, $action, $validated);

            return redirect(route('admin.advisory-boards.nomenclature.field-of-actions.edit', $action))
                ->with('success', trans_choice('validation.attributes.field_of_action', 1) . " " . __('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Destroy the resource.
     *
     * @return RedirectResponse
     */
    public function destroy(AdvisoryBoardNomenclatureFieldOfAction $action)
    {
        if (request()->user()->cant('delete', $action)) {
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.unauthorized'));
        }

        try {
            $action->delete();

            return redirect(route('admin.advisory-boards.nomenclature.field-of-actions.index', $action))
                ->with('success', trans_choice('validation.attributes.field_of_action', 1) . " " . __('messages.deleted_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return to_route('admin.work_regime')->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param AdvisoryBoardNomenclatureFieldOfAction $action
     *
     * @return RedirectResponse
     */
    public function restore(AdvisoryBoardNomenclatureFieldOfAction $action)
    {
        $this->authorize('restore', $action);

        try {
            $action->restore();

            return redirect()->route('admin.advisory-boards.nomenclature.field-of-actions.index')
                ->with('success', trans_choice('custom.function', 1) . ' ' . __('messages.restored_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('danger', __('messages.system_error'));
        }
    }

    private function filters($request)
    {
        return array(
            'name' => array(
                'type' => 'text',
                'placeholder' => __('validation.attributes.name'),
                'value' => $request->input('name'),
                'col' => 'col-md-4'
            ),
            'deleted' => [
                'type' => 'checkbox',
                'placeholder' => __('custom.show') . ' ' . \Str::lower(__('custom.all_deleted')),
                'checked' => $request->input('deleted'),
                'value' => 1,
                'col' => 'col-md-auto'
            ],
//            <div class="col-xs-12 col-auto mb-2">
//                                <input id="deleted" type="checkbox" name="deleted" value="1" @if(request()->offsetGet('deleted')) checked @endif/>
//                                <label for="deleted" class="mb-0">{{ __('custom.show') . ' ' . __('custom.deleted_many') }}</label>
//                            </div>
        );
    }
}
