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

        $keywords = request()->offsetGet('keywords');

        $items = AdvisoryBoard::withTrashed()->with(['policyArea', 'translations'])
            ->where(function ($query) use ($keywords) {
                $query->when(!empty($keywords), function ($query) use ($keywords) {
                    $query->whereHas('translations', function ($query) use ($keywords) {
                        $query->where('name', 'like', '%' . $keywords . '%');
                    });
                });
            })
            ->where('active', false)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->view('admin.advisory-boards.archive.index', compact('items'));
    }
}
