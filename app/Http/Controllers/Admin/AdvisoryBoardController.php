<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AdvisoryBoard\DeleteAdvisoryBoardRequest;
use App\Http\Requests\Admin\AdvisoryBoard\RestoreAdvisoryBoardRequest;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardRequest;
use App\Models\AdvisoryActType;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMember;
use App\Models\AdvisoryChairmanType;
use App\Models\ConsultationLevel;
use App\Models\PolicyArea;
use App\Models\StrategicDocuments\Institution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdvisoryBoardController extends AdminController
{

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $items = AdvisoryBoard::withTrashed()->with(['policyArea'])->orderBy('id', 'desc')->paginate(10);

        return $this->view('admin.advisory-boards.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $item = new AdvisoryBoard();
        $policy_areas = PolicyArea::orderBy('id')->get();
        $advisory_chairman_types = AdvisoryChairmanType::orderBy('id')->get();
        $advisory_act_types = AdvisoryActType::orderBy('id')->get();
        $institutions = Institution::with('translations')->select('id')->orderBy('id')->get();

        return $this->view(
            'admin.advisory-boards.create',
            compact('item', 'policy_areas', 'advisory_chairman_types', 'advisory_act_types', 'institutions')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdvisoryBoardRequest $request
     *
     * @return RedirectResponse
     */
    public function store(StoreAdvisoryBoardRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $item = new AdvisoryBoard();
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();

            $this->storeTranslateOrNew(AdvisoryBoard::TRANSLATABLE_FIELDS, $item, $validated);

            DB::commit();
            return redirect()->route('admin.advisory-boards.index')
                ->with('success', trans_choice('custom.advisory_boards', 1) . " " . __('messages.created_successfully_m'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param AdvisoryBoard $item
     *
     * @return View
     */
    public function show(AdvisoryBoard $item)
    {
        return $this->view('admin.advisory-boards.view', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AdvisoryBoard $item
     *
     * @return View
     */
    public function edit(AdvisoryBoard $item): View
    {
        $policy_areas = PolicyArea::orderBy('id')->get();
        $advisory_chairman_types = AdvisoryChairmanType::orderBy('id')->get();
        $advisory_act_types = AdvisoryActType::orderBy('id')->get();
        $institutions = Institution::with('translations')->select('id')->orderBy('id')->get();
        $consultation_levels = ConsultationLevel::with('translations')->orderBy('id')->get();
        $members = AdvisoryBoardMember::withTrashed()->where('advisory_board_id', $item->id)->orderBy('id')->get();

        return $this->view(
            'admin.advisory-boards.edit',
            compact(
                'item',
                'policy_areas',
                'advisory_chairman_types',
                'advisory_act_types',
                'institutions',
                'consultation_levels',
                'members'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdvisoryBoardRequest $request
     * @param AdvisoryBoard              $item
     *
     * @return RedirectResponse
     */
    public function update(UpdateAdvisoryBoardRequest $request, AdvisoryBoard $item): RedirectResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();

            $this->storeTranslateOrNew(AdvisoryBoard::TRANSLATABLE_FIELDS, $item, $validated);

            DB::commit();
            return redirect()->route('admin.advisory-boards.edit', $item)
                ->with('success', trans_choice('custom.advisory_boards', 1) . " " . __('messages.updated_successfully_m'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteAdvisoryBoardRequest $request
     * @param AdvisoryBoard              $item
     *
     * @return RedirectResponse
     */
    public function destroy(DeleteAdvisoryBoardRequest $request, AdvisoryBoard $item): RedirectResponse
    {
        try {
            $item->delete();

            return redirect()->route('admin.advisory-boards.index')
                ->with('success', trans_choice('custom.advisory_boards', 1) . " $item->name " . __('messages.deleted_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect(route('admin.legislative_initiatives.index', $item))->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Restore the specified resource.
     */
    public function restore(RestoreAdvisoryBoardRequest $request, AdvisoryBoard $item)
    {
        try {
            $item->restore();

            return redirect()->route('admin.advisory-boards.index')
                ->with('success', trans_choice('custom.advisory_boards', 1) . " $item->name " . __('messages.restored_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('danger', __('messages.system_error'));
        }
    }
}
