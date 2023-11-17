<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormInput;
use Illuminate\Http\Request;

class ImpactAssessmentController extends AdminController
{
    const LIST_ROUTE = 'admin.impact_assessment';
    const LIST_VIEW = 'admin.impact_assessment.index';

    public function index(Request $request)
    {
        $items = FormInput::with(['user'])->orderBy('created_at', 'desc')->get();
        $listRouteName = self::LIST_ROUTE;
        return $this->view(self::LIST_VIEW, compact('items','listRouteName'));
    }

}
