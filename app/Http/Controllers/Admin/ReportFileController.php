<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportFileRequest;
use App\Http\Requests\UpdateReportFileRequest;
use App\Models\ReportFile;

class ReportFileController extends Controller
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
     * @param  \App\Http\Requests\StoreReportFileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReportFileRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReportFile  $reportFile
     * @return \Illuminate\Http\Response
     */
    public function show(ReportFile $reportFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReportFile  $reportFile
     * @return \Illuminate\Http\Response
     */
    public function edit(ReportFile $reportFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateReportFileRequest  $request
     * @param  \App\Models\ReportFile  $reportFile
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReportFileRequest $request, ReportFile $reportFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReportFile  $reportFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReportFile $reportFile)
    {
        //
    }
}
