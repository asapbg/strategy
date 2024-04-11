<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Models\ConsultationType;
use App\Models\FieldOfAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index()
    {
        return $this->view('admin.reports.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $actions = FieldOfAction::orderByTranslation('name')->get();
        $types = ConsultationType::orderBy('id')->get();

        return $this->view('admin.reports.create', compact('actions', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreReportRequest $request
     *
     * @return RedirectResponse
     */
    public function store(StoreReportRequest $request)
    {
        $validated = $request->validated();
        dd($validated);
        try {
            $action = FieldOfAction::create([
                'name_bg' => $validated['name_bg'],
                'name_en' => $validated['name_en'],
            ]);

            return redirect(route('admin.nomenclature.field_of_actions.index', $action))
                ->with('success', trans_choice('validation.attributes.field_of_action', 1) . " " . __('messages.created_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }
}
