<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AdvisoryBoardFunction\StoreAdvisoryBoardFunctionRequest;
use App\Http\Requests\UpdateAdvisoryBoardFunctionRequest;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardFunction;
use DB;
use Illuminate\Http\RedirectResponse;
use Log;

class AdvisoryBoardFunctionController extends AdminController
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
            $item = AdvisoryBoardFunction::where('advisory_board_id', $advisory_board_id)->first() ?? new AdvisoryBoardFunction();
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

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AdvisoryBoardFunction $advisoryBoardFunction
     *
     * @return \Illuminate\Http\Response
     */
    public function show(AdvisoryBoardFunction $advisoryBoardFunction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AdvisoryBoardFunction $advisoryBoardFunction
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(AdvisoryBoardFunction $advisoryBoardFunction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateAdvisoryBoardFunctionRequest $request
     * @param \App\Models\AdvisoryBoardFunction                     $advisoryBoardFunction
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdvisoryBoardFunctionRequest $request, AdvisoryBoardFunction $advisoryBoardFunction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AdvisoryBoardFunction $advisoryBoardFunction
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvisoryBoardFunction $advisoryBoardFunction)
    {
        //
    }
}
