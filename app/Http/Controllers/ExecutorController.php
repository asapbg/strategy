<?php

namespace App\Http\Controllers;

use App\Models\Executor;
use Illuminate\View\View;

class ExecutorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $executors = Executor::orderBy('id', 'desc')->get();

        return $this->view('executors.index', compact('executors'));
    }
}
