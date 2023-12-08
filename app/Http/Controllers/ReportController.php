<?php

namespace App\Http\Controllers;

class ReportController extends Controller
{
    public function index()
    {
        $pageTitle = trans_choice('custom.reports', 2);

        return $this->view('site.reports.index', compact("pageTitle"));
    }

    public function show()
    {
        $pageTitle = trans_choice('custom.reports', 2);
        return $this->view('site.reports.view', compact("pageTitle"));
    }
}
