<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Executor;
use App\Http\Requests\StoreExecutorRequest;
use App\Http\Requests\UpdateExecutorRequest;
use Illuminate\View\View;

class ExecutorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $executors = Executor::orderBy('id', 'desc')->get();

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
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExecutorRequest $request)
    {
        dd($request->all());

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
     * @return \Illuminate\Http\Response
     */
    public function edit(Executor $executor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateExecutorRequest $request
     * @param Executor $executor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExecutorRequest $request, Executor $executor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Executor $executor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Executor $executor)
    {
        //
    }
}
