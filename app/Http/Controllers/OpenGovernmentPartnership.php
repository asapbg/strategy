<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class OpenGovernmentPartnership extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {

        return $this->view('site.ogp.index');

    }
}
