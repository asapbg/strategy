<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreAdvisoryBoardMemberRequest;
use App\Http\Requests\UpdateAdvisoryBoardMemberRequest;
use App\Models\AdvisoryBoardMember;
use DB;
use Log;

class AdvisoryBoardMemberController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreAdvisoryBoardMemberRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdvisoryBoardMemberRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $item = new AdvisoryBoardMember();
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();

            $validated['advisory_member_id'] = $item->id;

            $this->storeTranslateOrNew(AdvisoryBoardMember::TRANSLATABLE_FIELDS, $item, $validated);

            DB::commit();
            return redirect()->route('admin.advisory-boards.index')
                ->with('success', trans_choice('custom.advisory_boards', 1) . " " . __('messages.created_successfully_m'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AdvisoryBoardMember $advisoryBoardMember
     *
     * @return \Illuminate\Http\Response
     */
    public function show(AdvisoryBoardMember $advisoryBoardMember)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AdvisoryBoardMember $advisoryBoardMember
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(AdvisoryBoardMember $advisoryBoardMember)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateAdvisoryBoardMemberRequest $request
     * @param \App\Models\AdvisoryBoardMember                     $advisoryBoardMember
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdvisoryBoardMemberRequest $request, AdvisoryBoardMember $advisoryBoardMember)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AdvisoryBoardMember $advisoryBoardMember
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvisoryBoardMember $advisoryBoardMember)
    {
        //
    }
}
