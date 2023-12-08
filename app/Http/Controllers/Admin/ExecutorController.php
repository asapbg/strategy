<?php

namespace App\Http\Controllers\Admin;

use App\Models\Executor;
use App\Http\Requests\StoreExecutorRequest;
use App\Http\Requests\UpdateExecutorRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ExecutorController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $paginate = $request->filled('paginate') ? $request->get('paginate') : Executor::PAGINATE;
        $active = $request->filled('active') ? $request->get('active') : 1;
        $contractor_name = $request->get('contractor_name');
        $executor_name = $request->get('executor_name');
        $contract_date = $request->get('contract_date');
        $contract_date_from = $request->get('contract_date_from');
        $contract_date_till = $request->get('contract_date_till');

        $executors = Executor::select('executors.*')
            ->with('translation')
            ->joinTranslation(Executor::class)
            ->whereLocale(app()->getLocale())
            ->when($contractor_name, function ($query, $contractor_name) {
                return $query->where('contractor_name', 'ILIKE', "%$contractor_name%");
            })
            ->when($contractor_name, function ($query, $contractor_name) {
                return $query->where('contractor_name', 'ILIKE', "%$contractor_name%");
            })
            ->when($executor_name, function ($query, $executor_name) {
                return $query->where('executor_name', 'ILIKE', "%$executor_name%");
            })
            ->when($contract_date, function ($query, $contract_date) {
                return $query->where('contract_date', databaseDate($contract_date));
            })
            ->when($contract_date_from, function ($query, $contract_date_from) {
                return $query->where('contract_date', '>=', databaseDate($contract_date_from));
            })
            ->when($contract_date_till, function ($query, $contract_date_till) {
                return $query->where('contract_date', '<=', databaseDate($contract_date_till));
            })
            ->whereActive($active)
            ->orderBy('executors.id', 'desc')
            ->paginate($paginate);


        return $this->view('admin.executors.index', compact('executors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return $this->view('admin.executors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreExecutorRequest $request
     * @return RedirectResponse
     */
    public function store(StoreExecutorRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {

            $item = new Executor();
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();

            $this->storeTranslateOrNew($item->translatedAttributes, $item, $validated);

            DB::commit();

            return to_route('admin.executors.index')->with('success', __('The new record was created successfully'));

        } catch (Exception $e) {

            Log::error($e);

            DB::rollBack();

            $this->backWithError('danger', __('messages.system_error'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Executor $executor
     * @return \Illuminate\Http\Response
     */
    public function show(Executor $executor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Executor $executor
     * @return View
     */
    public function edit(Executor $executor)
    {
        return $this->view('admin.executors.edit', compact('executor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateExecutorRequest $request
     * @param Executor $executor
     * @return RedirectResponse
     */
    public function update(UpdateExecutorRequest $request, Executor $executor)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {

            $fillable = $this->getFillableValidated($validated, $executor);
            $executor->fill($fillable);
            $executor->save();

            $this->storeTranslateOrNew(Executor::TRANSLATABLE_FIELDS, $executor, $validated);

            DB::commit();

            return to_route('admin.executors.index')->with('success', __('The record was updated successfully'));

        } catch (Exception $e) {

            Log::error($e);

            DB::rollBack();

            $this->backWithError('danger', __('messages.system_error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Executor $executor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Executor $executor)
    {
        try {

            $executor->translations()->delete();
            $executor->delete();

            return to_route('admin.users')
                ->with('success', "Записът ".__('messages.deleted_successfully_m'));
        }
        catch (Exception $e) {

            Log::error($e);

            $this->backWithError('danger', __('messages.system_error'));

        }
    }
}
