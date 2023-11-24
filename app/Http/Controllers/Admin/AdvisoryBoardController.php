<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreAdvisoryBoardRequest;
use App\Http\Requests\UpdateAdvisoryBoardRequest;
use App\Models\AdvisoryActType;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryChairmanType;
use App\Models\PolicyArea;
use App\Models\StrategicDocuments\Institution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
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
        $items = AdvisoryBoard::orderBy('id', 'desc')->paginate(10);

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
        $institutions = Institution::orderBy('id')->get();

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
     * @param AdvisoryBoard $advisory_board
     *
     * @return View
     */
    public function show(AdvisoryBoard $advisory_board)
    {
        return $this->view('admin.advisory-boards.view', compact('advisory_board'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AdvisoryBoard $advisoryBoard
     *
     * @return Response
     */
    public function edit(AdvisoryBoard $advisoryBoard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateAdvisoryBoardRequest $request
     * @param \App\Models\AdvisoryBoard                     $advisoryBoard
     *
     * @return Response
     */
    public function update(UpdateAdvisoryBoardRequest $request, AdvisoryBoard $advisoryBoard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AdvisoryBoard $advisoryBoard
     *
     * @return Response
     */
    public function destroy(AdvisoryBoard $advisoryBoard)
    {
        //
    }
}
