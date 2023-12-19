<?php

namespace App\Http\Controllers;

use App\Enums\OgpStatusEnum;
use App\Models\OgpPlan;
use App\Models\OgpPlanArea;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NationalActionPlans extends Controller
{
    /**
     * List of all otg_areas
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $items = OgpPlan::Active()
            ->whereRelation('status', 'type', OgpStatusEnum::ACTIVE->value)
            ->FilterBy($request->all())
            ->orderBy('created_at', 'desc')
            ->paginate(OgpPlan::PAGINATE);

        $route_view_name = 'ogp.national_action_plans.show';

        return $this->view('site.ogp.plans', compact('items', 'route_view_name'));
    }

    /**
     * @param Request $request
     * @param OgpPlan $plan
     * @return View
     */
    public function show(Request $request, $id): View
    {
        $plan = OgpPlan::whereRelation('status', 'type', OgpStatusEnum::ACTIVE->value)->findOrFail($id);
        return $this->view('site.ogp.plan_show', compact('plan'));
    }

    /**
     * @param Request $request
     * @param OgpPlan $plan
     * @param OgpPlanArea $area
     * @return View
     */
    public function area(Request $request, OgpPlan $plan, OgpPlanArea $planArea): View
    {
        return $this->view('site.ogp.plan_area_show', compact('plan', 'planArea'));
    }

}
