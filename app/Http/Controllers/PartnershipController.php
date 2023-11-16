<?php

namespace App\Http\Controllers;

class PartnershipController extends Controller
{
    public function index()
    {
        return $this->view('site.partnerships.index');
    }
}
