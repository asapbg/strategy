<?php

namespace App\Http\Controllers\Admin\Nomenclature;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFieldOfActionRequest;
use App\Http\Requests\UpdateFieldOfActionRequest;
use App\Models\FieldOfAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class FieldOfActionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $actions = FieldOfAction::orderBy('id')->paginate(FieldOfAction::PAGINATION);

        return $this->view('admin.nomenclatures.field_of_actions.index', compact('actions'));
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

        try {
            $action = FieldOfAction::create([
                'name_bg' => $validated['name_bg'],
                'name_en' => $validated['name_en']
            ]);

            return redirect(route('admin.nomenclature.field_of_actions.index', $action))
                ->with('success', trans_choice('validation.attributes.field_of_action', 1) . " " . __('messages.created_successfully_f'));
        } catch (\Exception $e) {
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
    public function edit(FieldOfAction $action): View
    {
        return $this->view('admin.nomenclatures.field_of_actions.edit', compact('action'));
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
            $action->fill($validated);
            $action->save();

            return redirect(route('admin.nomenclature.field_of_actions.edit', $action))
                ->with('success', trans_choice('validation.attributes.field_of_action', 1) . " " . __('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }
}
