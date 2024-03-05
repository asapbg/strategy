<?php

namespace App\Http\Controllers;

use App\Enums\OgpStatusEnum;
use App\Models\OgpPlan;
use App\Models\OgpPlanArea;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NationalActionPlans extends Controller
{
    private $pageTitle;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->title_singular = __('custom.open_government_partnership');
        $this->pageTitle = __('custom.open_government_partnership');
    }

    /**
     * List of all otg_areas
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $items = OgpPlan::Active()
            ->National()
            ->whereRelation('status', 'type', OgpStatusEnum::ACTIVE->value)
            ->FilterBy($request->all())
            ->orderBy('created_at', 'desc')
            ->paginate(OgpPlan::PAGINATE);

        $route_view_name = 'ogp.national_action_plans.show';

        $pageTitle = $this->pageTitle;
        $this->composeBreadcrumbs();
        return $this->view('site.ogp.plans', compact('pageTitle', 'items', 'route_view_name'));
    }

    /**
     * @param Request $request
     * @param OgpPlan $plan
     * @return View
     */
    public function show(Request $request, $id): View
    {
        $plan = OgpPlan::whereRelation('status', 'type', OgpStatusEnum::ACTIVE->value)->find($id);
        if(!$plan){
            return back()->with('warning', __('messages.record_not_found'));
        }
        $pageTitle = $plan->name;
        $this->composeBreadcrumbs($plan);
        return $this->view('site.ogp.plan_show', compact('pageTitle', 'plan'));
    }

    public function export(Request $request, $id)
    {
        $plan = OgpPlan::whereRelation('status', 'type', OgpStatusEnum::ACTIVE->value)->findOrFail($id);
        if(!$plan){
            return back()->with('warning', __('messages.record_not_found'));
        }

        $exportData = [
            'title' => $plan->name,
            'content' => $plan->content,
            'rows' => $plan->areas
        ];
        $fileName = 'national_plan_'.date('d_m_Y_H_i_s').'.pdf';

        $pdf = PDF::loadView('exports.ogp_national_plan', ['data' => $exportData, 'isPdf' => true]);
        return $pdf->download($fileName);
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

    /**
     * @param $item
     * @param $extraItems
     * @return void
     */
    private function composeBreadcrumbs($item = null, $extraItems = []){
        $customBreadcrumbs = array(
            ['name' => __('custom.open_government_partnership'), 'url' => route('ogp.list')],
            ['name' => __('custom.national_action_plans'), 'url' => route('ogp.national_action_plans')]
        );

        if($item){
            $customBreadcrumbs[] = ['name' => $item->name, 'url' => !empty($extraItems) ? route('ogp.national_action_plans.show', $item->id) : null];
        }
        if(!empty($extraItems)){
            foreach ($extraItems as $eItem){
                $customBreadcrumbs[] = $eItem;
            }
        }
        $this->setBreadcrumbsFull($customBreadcrumbs);
    }

}
