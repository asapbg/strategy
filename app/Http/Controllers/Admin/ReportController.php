<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()
    {
        return $this->view('admin.reports.index');
    }

    public function edit()
    {
        return $this->view('admin.reports.view');
    }
}
