<?php

namespace App\Http\Controllers;

class AnalyzeMethodsController extends Controller
{
    public function index()
    {
        return $this->view('site.impact-methods.index');
    }
}
