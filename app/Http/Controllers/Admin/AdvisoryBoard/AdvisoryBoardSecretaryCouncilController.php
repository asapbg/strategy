<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardSecretaryCouncilRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardSecretaryCouncilRequest;
use App\Models\AdvisoryBoardSecretaryCouncil;
use DB;
use Illuminate\Http\JsonResponse;
use Log;

class AdvisoryBoardSecretaryCouncilController extends AdminController
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
     * Display the specified resource.
     *
     * @param \App\Models\AdvisoryBoardSecretaryCouncil $advisoryBoardSecretaryCouncil
     *
     * @return \Illuminate\Http\Response
     */
    public function show(AdvisoryBoardSecretaryCouncil $advisoryBoardSecretaryCouncil)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AdvisoryBoardSecretaryCouncil $advisoryBoardSecretaryCouncil
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(AdvisoryBoardSecretaryCouncil $advisoryBoardSecretaryCouncil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardSecretaryCouncilRequest $request
     * @param \App\Models\AdvisoryBoardSecretaryCouncil                                         $advisoryBoardSecretaryCouncil
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdvisoryBoardSecretaryCouncilRequest $request, AdvisoryBoardSecretaryCouncil $advisoryBoardSecretaryCouncil)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AdvisoryBoardSecretaryCouncil $advisoryBoardSecretaryCouncil
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvisoryBoardSecretaryCouncil $advisoryBoardSecretaryCouncil)
    {
        //
    }
}
