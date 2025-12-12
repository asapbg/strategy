<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class NomenclatureController extends AdminController
{

    /**
     * Show the Admin's dashboard.
     *
     * @return View
     */
    public function index()
    {
        return $this->view('admin.nomenclatures');
    }
}
