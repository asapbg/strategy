<?php

namespace App\Http\Controllers\Admin\Nomenclature;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StoreFieldOfActionRequest;
use App\Http\Requests\UpdateFieldOfActionRequest;
use App\Models\FieldOfAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class FieldOfActionController extends AdminController
{

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $requestFilter = $request->all();
        $active = $request->get('active', 1);
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? FieldOfAction::PAGINATE;
        $actions = FieldOfAction::orderBy('parentid')
            ->orderByTranslation('name')
            ->FilterBy($requestFilter)
            ->whereActive($active)
            ->paginate($paginate);
        $toggleBooleanModel = 'FieldOfAction';
        return $this->view('admin.nomenclatures.field_of_actions.index', compact('actions', 'filter', 'toggleBooleanModel'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return $this->view('admin.nomenclatures.field_of_actions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFieldOfActionRequest $request
     *
     * @return RedirectResponse
     */
    public function store(StoreFieldOfActionRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $field_of_action = new FieldOfAction();
            $fillable = $this->getFillableValidated($validated, $field_of_action);
            $field_of_action->fill($fillable);
            $field_of_action->save();

            $this->storeTranslateOrNew(FieldOfAction::TRANSLATABLE_FIELDS, $field_of_action, $validated);

            DB::commit();
            return redirect(route('admin.nomenclature.field_of_actions.index'))
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
     * @param FieldOfAction $action
     *
     * @return View
     */
    public function edit(FieldOfAction $item): View
    {
        $translatableFields = FieldOfAction::translationFieldsProperties();

        return $this->view('admin.nomenclatures.field_of_actions.edit', compact('item', 'translatableFields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateFieldOfActionRequest $request
     * @param FieldOfAction              $action
     *
     * @return RedirectResponse
     */
    public function update(UpdateFieldOfActionRequest $request, FieldOfAction $action): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $fillable = $this->getFillableValidated($validated, $action);
            $action->fill($fillable);
            $action->save();
            $this->storeTranslateOrNew(FieldOfAction::TRANSLATABLE_FIELDS, $action, $validated);

            return redirect(route('admin.nomenclature.field_of_actions.edit', $action))
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
    public function destroy(FieldOfAction $action)
    {
        if (request()->user()->cant('delete', $action)) {
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.unauthorized'));
        }

        try {
            $action->delete();

            return redirect(route('admin.nomenclature.field_of_actions.index', $action))
                ->with('success', trans_choice('validation.attributes.field_of_action', 1) . " " . __('messages.deleted_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return to_route('admin.work_regime')->with('danger', __('messages.system_error'));

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
            )
        );
    }
}
