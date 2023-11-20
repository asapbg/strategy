<?php

namespace App\Http\Controllers;

class ReportController extends Controller
{
    public function index()
    {
        return $this->view('site.reports.index');
    }

    public function show()
    {
        return $this->view('site.reports.view');
    }
}
