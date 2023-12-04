<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Admin\AdminController;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardFunction;
use Illuminate\View\View;

class AdvisoryBoardArchiveController extends AdminController
{

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $this->authorize('viewAny', AdvisoryBoard::class);

        $programs = AdvisoryBoardFunction::with('files')->orderBy('created_at', 'desc')->paginate(10);

        return $this->view('admin.advisory-boards.archive.index', compact('programs'));
    }
}
