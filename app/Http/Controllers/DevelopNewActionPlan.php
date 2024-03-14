<?php

namespace App\Http\Controllers;

use App\Enums\OgpStatusEnum;
use App\Http\Requests\OgpPlanAreaOfferRequest;
use App\Models\OgpPlan;
use App\Models\OgpPlanArea;
use App\Models\OgpPlanAreaOffer;
use App\Models\OgpPlanAreaOfferComment;
use App\Models\OgpPlanAreaOfferVote;
use App\Models\OgpPlanSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class DevelopNewActionPlan extends Controller
{
    private $pageTitle;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->title_singular = __('custom.open_government_partnership');
        $this->pageTitle = __('custom.develop_new_action_plan');
    }

    /**
     * List of all otg_areas
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $item = OgpPlan::select('ogp_plan.*')
            ->Active()
            ->join('ogp_status', 'ogp_plan.ogp_status_id', '=', 'ogp_status.id')
            ->leftJoin('ogp_plan_translations', function ($j){
                $j->on('ogp_plan_translations.ogp_plan_id', '=', 'ogp_plan.id')
                    ->where('ogp_plan_translations.locale', '=', app()->getLocale());
            })
            ->where('ogp_status.type', OgpStatusEnum::IN_DEVELOPMENT->value)
            ->orderBy('ogp_plan.created_at', 'desc')
            ->first();

        $pageTitle = $this->pageTitle;
        $this->composeBreadcrumbs($item);

        $schedules = [];
        if($item && $item->schedules->count()){
            foreach ($item->schedules()->orderBy('start_date','desc')->get() as $event){
                $schedules[] = array(
                    "id" => $event->id,
                    "title" => $event->name,
                    "description" => $event->description ? clearAfterStripTag(strip_tags(html_entity_decode($event->description))) : '',
                    "description_html" => $event->description ? strip_tags(html_entity_decode($event->description)) : '',
                    "start" => Carbon::parse($event->start_date)->startOfDay()->format('Y-m-d H:i:s'),
                    "end" => !empty($event->end_date) ? Carbon::parse($event->end_date)->endOfDay()->format('Y-m-d H:i:s') : Carbon::parse($event->start_date)->endOfDay()->format('Y-m-d H:i:s'),
                    "backgroundColor" => (Carbon::parse($event->start_date)->startOfDay()->format('Y-m-d') > Carbon::now()->startOfDay()->format('Y-m-d') ? '#00a65a' : '#00c0ef'),
                    "borderColor" => (Carbon::parse($event->start_date)->startOfDay()->format('Y-m-d') > Carbon::now()->startOfDay()->format('Y-m-d') ? '#00a65a' : '#00c0ef'),
                    "oneDay" => empty($event->end_date)
                );
            }
        }
        return $this->view('site.ogp.develop_new_action_plan.plan_show', compact('item', 'pageTitle', 'schedules'));
    }

//    /**
//     * @param Request $request
//     * @param OgpPlan $plan
//     * @return View
//     */
//    public function show(Request $request, $id): View
//    {
//        $plan = OgpPlan::whereRelation('status', 'type', OgpStatusEnum::IN_DEVELOPMENT->value)
//            ->orWhereRelation('status', 'type', OgpStatusEnum::FINAL->value)
//            ->findOrFail($id);
//
//        $pageTitle = $this->pageTitle;
//        $this->composeBreadcrumbs($plan);
//        return $this->view('site.ogp.plan_show', compact('plan', 'pageTitle'));
//    }

    /**
     * @param Request $request
     * @param OgpPlan $plan
     * @param OgpPlanArea $area
     * @return View
     */
    public function area(Request $request, OgpPlan $plan, OgpPlanArea $planArea): View
    {
        if(auth()->user()->cannot('viewPublic', $plan)) {
            return redirect(route('ogp.develop_new_action_plans'))->with('warning', __('messages.no_rights_to_view_content'));
        }
        $pageTitle = $this->pageTitle;
        $this->composeBreadcrumbs($plan, array(['name' => $planArea->area->name, 'url' => '']));
        return $this->view('site.ogp.develop_new_action_plan.plan_area_show', compact('plan', 'planArea', 'pageTitle'));
    }

    public function offer(Request $request, OgpPlan $plan, OgpPlanArea $planArea, OgpPlanAreaOffer $offer): View
    {
        if(auth()->user()->cannot('viewPublic', $plan)) {
            return redirect(route('ogp.develop_new_action_plans'))->with('warning', __('messages.no_rights_to_view_content'));
        }

        $pageTitle = $this->pageTitle;
        $this->composeBreadcrumbs($plan, array(['name' => $planArea->area->name, 'url' => '']));
        return $this->view('site.ogp.develop_new_action_plan.plan_area_offer_show', compact('plan', 'planArea', 'pageTitle', 'offer'));
    }

    public function store(OgpPlanAreaOfferRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $validated['users_id'] = $request->user()->id;
        $item = OgpPlanArea::find($id);
        $offer_id = (int)$request->get('offer', 0);


        if(!$id || $item->plan->status->type != OgpStatusEnum::IN_DEVELOPMENT->value){
            return redirect(route('ogp.develop_new_action_plans'))->with('warning', __('messages.no_rights_to_view_content'));
        }

        DB::beginTransaction();

        try {
            if($offer_id) {
                $offer = OgpPlanAreaOffer::find($offer_id);
                $offer->content = $validated['content'];
                $offer->save();
            } else {
                //create new offer
                $offer = $item->offers()->create([
                    'users_id' => $user->id,
                    'content' => $validated['content']
                ]);
            }

            DB::commit();
            return redirect(route('ogp.develop_new_action_plans.area', ['plan' => $item->ogp_plan_id, 'planArea' => $item->id]))
                ->with('success', trans_choice('ogp.proposals', 1)." ".($offer_id ? __('messages.updated_successfully_n') : __('messages.created_successfully_n')));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function editOffer(Request $request, OgpPlanAreaOffer $offer): View|\Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        if($user->cannot('update', $offer)) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }

        $planArea = $offer->planArea;

        return $this->view('site.ogp.offer.edit', compact('offer', 'planArea'));
    }

    public function storeComment(Request $request, OgpPlanAreaOffer $offer): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();

        if($user->cannot('createComment', $offer)) {
            return response()->json([
                'error' => 1,
                'message' => __('messages.no_rights_to_view_content')
            ], 200);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 1,
                'message' => __('ogp.comment_field_required')
            ], 200);
        }

        $comment = $offer->comments()->create([
            'content' => $request->get('content'),
            'users_id' => $user->id
        ]);

        return response()->json([
            'error' => 0
        ], 200);
    }

    public function deleteComment(Request $request, OgpPlanAreaOfferComment $comment): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        if($user->cannot('delete', $comment)) {
            return response()->json([
                'error' => 1,
                'message' => __('messages.no_rights_to_view_content')
            ]);
        }

        try {
            $comment->delete();
            return response()->json([
                'error' => 0,
                'row_id' => $request->get('row_id')
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'error' => 1,
                'message' => __('messages.system_error')
            ]);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @param int $like
     * @return \Illuminate\Http\JsonResponse
     */
    public function voteOffer(Request $request, $id, $like = 0): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $offer = OgpPlanAreaOffer::findOrFail($id);
        $container = $request->get('container');

        if($user->cannot('vote', $offer)) {
            return response()->json([
                'error' => 1,
                'message' => __('messages.no_rights_to_view_content')
            ]);
        }

        try {
            $vote = new OgpPlanAreaOfferVote(['is_like' => $like, 'users_id' => $user->id]);
            $offer->votes()->save($vote);

            $offer->refresh();

            return response()->json([
                'error' => 0,
                'container' => $container,
                'html' => view('site.ogp.partial.vote', [
                    'item' => $offer,
                    'route' => 'ogp.develop_new_action_plans.vote',
                    'container' => $container
                ])->render(),
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'error' => 1,
                'message' => __('messages.system_error')
            ]);
        }

    }

    /**
     * @param $item
     * @param $extraItems
     * @return void
     */
    private function composeBreadcrumbs($item = null, $extraItems = []){
        $customBreadcrumbs = array(
            ['name' => __('custom.open_government_partnership'), 'url' => route('ogp.list')],
            ['name' => __('custom.develop_new_action_plan'), 'url' => route('ogp.develop_new_action_plans')]
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

    private function filters($request, $currentRequest)
    {
        return array(
            'title' => array(
                'type' => 'text',
                'label' => __('custom.search_in_title_content'),
                'value' => $request->input('title'),
                'col' => 'col-md-4'
            ),
            'fromDate' => array(
                'type' => 'datepicker',
                'value' => $request->input('fromDate'),
                'label' => __('ogp.from_date'),
                'col' => 'col-md-4'
            ),
            'toDate' => array(
                'type' => 'datepicker',
                'value' => $request->input('toDate'),
                'label' => __('ogp.to_date'),
                'col' => 'col-md-4'
            ),
            'paginate' => array(
                'type' => 'select',
                'options' => paginationSelect(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.filter_pagination'),
                'value' => $request->input('paginate') ?? OgpPlan::PAGINATE,
                'col' => 'col-md-3'
            ),

        );
    }

    private function sorters()
    {
        return array(
            'title' => ['class' => 'col-md-2', 'label' => __('custom.title')],
            'fromDate' => ['class' => 'col-md-3', 'label' => __('ogp.from_date')],
            'toDate' => ['class' => 'col-md-3', 'label' => __('ogp.to_date')],
        );
    }

}
