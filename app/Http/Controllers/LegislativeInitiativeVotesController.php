<?php

namespace App\Http\Controllers;

use App\Models\LegislativeInitiativeVote;
use App\Http\Requests\StoreLegislativeInitiativeVotesRequest;
use App\Http\Requests\UpdateLegislativeInitiativeVotesRequest;

class LegislativeInitiativeVotesController extends Controller
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
     * @param  \App\Http\Requests\StoreLegislativeInitiativeVotesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLegislativeInitiativeVotesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LegislativeInitiativeVote $legislativeInitiativeVotes
     *
     * @return \Illuminate\Http\Response
     */
    public function show(LegislativeInitiativeVote $legislativeInitiativeVotes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LegislativeInitiativeVote $legislativeInitiativeVotes
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(LegislativeInitiativeVote $legislativeInitiativeVotes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLegislativeInitiativeVotesRequest $request
     * @param \App\Models\LegislativeInitiativeVote                       $legislativeInitiativeVotes
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLegislativeInitiativeVotesRequest $request, LegislativeInitiativeVote $legislativeInitiativeVotes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\LegislativeInitiativeVote $legislativeInitiativeVotes
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(LegislativeInitiativeVote $legislativeInitiativeVotes)
    {
        //
    }
}
