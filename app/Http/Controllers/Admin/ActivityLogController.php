<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{

    /**
     * Show activity logs list.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $log_date = ($request->filled('activity_log_date')) ? $request->get('activity_log_date') : null;
        $date_start = "";
        $date_end = "";
        if ($log_date) {
            $date_start = databaseDateTime("$log_date 00:00:00");
            $date_end = databaseDateTime("$log_date 23:59:59");
        }
        $causer_id = ($request->filled('causer_id')) ? $request->get('causer_id') : null;
        $subject_type = ($request->filled('subject_type')) ? $request->get('subject_type') : null;

        $activities = CustomActivity::when($causer_id, function ($query) use ($causer_id) {
                                        $query->where('causer_id', $causer_id);
                                    })
                                    ->when($subject_type, function ($query) use ($subject_type) {
                                        $query->where('subject_type', $subject_type);
                                    })
                                    ->when($log_date, function ($query) use ($date_start, $date_end) {
                                        $query->whereBetween('created_at', [$date_start, $date_end]);
                                    })
                                    ->orderBy('id','desc')
                                    ->paginate(CustomActivity::PAGINATE);

        $causers = User::whereActive(true)
            ->whereHas('activities')
            ->orderBy('first_name', 'asc')
            ->get();

        //TODO activity why 'App\Models\StrategicDocumentChild' trigger error
        $subjects = CustomActivity::select('subject_type')
            ->distinct('subject_type')
            ->where('subject_type', '<>', 'App\Models\StrategicDocumentChild')
            ->where('subject_type', '<>', 'App\Models\AdvisoryBoardRegulatoryFramework')
            ->where('subject_type', '<>', 'App\Models\AdvisoryBoardRegulatoryFrameworkTranslation')
            ->where('subject_type', '<>', 'App\Models\OgpAreaArrangement')
            ->where('subject_type', '<>', 'App\Models\OgpAreaArrangementField')
            ->where('subject_type', '<>', 'App\Models\OgpAreaCommitment')
            ->get();

        return view('admin.activity-logs.index', compact('activities','causers', 'subjects'));
    }

    /**
     * Display the log details.
     *
     * @param  CustomActivity $activity
     * @return View
     */
    public function show(CustomActivity $activity)
    {
        if(auth()->user()->cannot('view', $activity)) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }

        return view('admin.activity-logs.show', compact('activity'));
    }

}
