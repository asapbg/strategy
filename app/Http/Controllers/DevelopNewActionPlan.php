<?php

namespace App\Http\Controllers;

use App\Enums\OgpStatusEnum;
use App\Http\Requests\OgpPlanAreaOfferRequest;
use App\Models\OgpPlan;
use App\Models\OgpPlanArea;
use App\Models\OgpPlanAreaOffer;
use App\Models\OgpPlanAreaOfferComment;
use App\Models\OgpPlanAreaOfferVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class DevelopNewActionPlan extends Controller
{
    /**
     * List of all otg_areas
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        //TODO: get only specific status for discussion
        $items = OgpPlan::Active()
            ->whereRelation('status', 'type', OgpStatusEnum::IN_DEVELOPMENT->value)
            ->orWhereRelation('status', 'type', OgpStatusEnum::FINAL->value)
            ->FilterBy($request->all())
            ->orderBy('created_at', 'desc')
            ->paginate(OgpPlan::PAGINATE);
        return $this->view('site.ogp.plans', compact('items'));
    }

    /**
     * @param Request $request
     * @param OgpPlan $plan
     * @return View
     */
    public function show(Request $request, $id): View
    {
        $plan = OgpPlan::whereRelation('status', 'type', OgpStatusEnum::IN_DEVELOPMENT->value)
            ->orWhereRelation('status', 'type', OgpStatusEnum::FINAL->value)
            ->findOrFail($id);
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

    public function store(OgpPlanAreaOfferRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $validated['users_id'] = $request->user()->id;
        $item = OgpPlanArea::findOrFail($id);
        $offer_id = $request->get('offer', 0);

        DB::beginTransaction();

        try {
            if($offer_id) {
                $offer = OgpPlanAreaOffer::findOrFail($offer_id);
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
            return to_route('ogp.develop_new_action_plans.area', ['plan' => $item->ogp_plan_id, 'planArea' => $item->id])
                ->with('success', trans_choice('custom.proposals', 1)." ".__('messages.updated_successfully_f'));
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
            ]);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 1,
                'message' => __('ogp.comment_field_required')
            ]);
        }

        $comment = $offer->comments()->create([
            'content' => $request->get('content'),
            'users_id' => $user->id
        ]);

        return response()->json([
            'error' => 0,
            'offer_id' => $offer->id,
            'html' => view('site.ogp.develop_new_action_plan.comment_row', compact('comment'))->render()
        ]);
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

}
