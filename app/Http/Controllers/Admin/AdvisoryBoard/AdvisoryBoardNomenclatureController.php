<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\View\View;

class AdvisoryBoardNomenclatureController extends AdminController
{

    /**
     * Show the Advisory Boards's nomenclatures.
     *
     * @return View
     */
    public function index()
    {
        return view('admin.advisory-boards.nomenclatures.index');
    }
}
