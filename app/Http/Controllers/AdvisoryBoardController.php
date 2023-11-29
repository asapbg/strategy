<?php

namespace App\Http\Controllers;

use App\Models\ActType;
use App\Models\AdvisoryActType;
use App\Models\AdvisoryChairmanType;
use App\Models\AuthorityAdvisoryBoard;
use App\Models\FieldOfAction;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvisoryBoardController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->title_singular = __('custom.legislative_initiatives');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $pageTitle = trans_choice('custom.advisory_boards', 2);

        $field_of_actions = FieldOfAction::orderBy('id')->get();
        $authority_advisory_boards = AuthorityAdvisoryBoard::orderBy('id')->get();
        $advisory_act_types = AdvisoryActType::orderBy('id')->get();
        $advisory_chairman_types = AdvisoryChairmanType::orderBy('id')->get();
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_ADVISORY_BOARDS.'_'.app()->getLocale())->first();
        return $this->view('site.advisory-boards.index', compact(
            'pageTitle',
            'field_of_actions',
            'authority_advisory_boards',
            'advisory_act_types',
            'advisory_chairman_types',
            'pageTopContent'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd('store');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AdvisoryBoard $advisoryBoard
     *
     * @return \Illuminate\Http\Response
     */
    public function show(AdvisoryBoard $advisoryBoard)
    {
        dd('show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     *
     * @param \App\Models\AdvisoryBoard $advisoryBoard
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(AdvisoryBoard $advisoryBoard)
    {
        dd('edit');
    }

    /**
     * Update the specified resource in storage.
     *
     *
     * @param \Illuminate\Http\Request  $request
     * @param \App\Models\AdvisoryBoard $advisoryBoard
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdvisoryBoard $advisoryBoard)
    {
        dd('update');
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @param \App\Models\AdvisoryBoard $advisoryBoard
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvisoryBoard $advisoryBoard)
    {
        dd('destroy');
    }
}
