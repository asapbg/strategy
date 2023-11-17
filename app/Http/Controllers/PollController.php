<?php

namespace App\Http\Controllers;

class PollController extends Controller
{
    public function index()
    {
        return $this->view('site.polls.index');
    }

    public function show()
    {
        return $this->view('site.polls.show');
    }
}
