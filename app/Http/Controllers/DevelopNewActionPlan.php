<?php

namespace App\Http\Controllers;

use App\Enums\OgpAreaArrangementFieldEnum;
use App\Http\Requests\OgpAreaOfferRequest;
use App\Models\OgpArea;
use App\Models\OgpAreaArrangement;
use App\Models\OgpAreaCommitment;
use App\Models\OgpAreaOffer;
use App\Models\OgpAreaOfferComment;
use Illuminate\Http\Request;
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
        $items = OgpArea::Active()
            ->FilterBy($request->all())
            ->orderBy('created_at', 'desc')
            ->paginate(OgpArea::PAGINATE);
        return $this->view('site.ogp.develop_new_action_plan', compact('items'));
    }

    public function show(Request $request, OgpArea $ogpArea): View
    {
        return $this->view('site.ogp.develop_new_action_plan_show', compact('ogpArea'));
    }

    public function store(OgpAreaOfferRequest $request, $otg_area_id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $validated['users_id'] = $request->user()->id;
        $item = OgpArea::findOrFail($otg_area_id);
        $fields = $request->get('fields');
        $offer_id = $request->get('offer', 0);

        try {
            if($offer_id) {
                $offer = OgpAreaOffer::findOrFail($offer_id);
            } else {
                //create new offer
                $offer = $item->offers()->create([
                    'users_id' => $user->id
                ]);
            }

            if($offer) {
                if(!is_null($validated['commitment_id']) && $validated['commitment_id']) {
                    $commitment = OgpAreaCommitment::findOrFail($validated['commitment_id']);
                } else {
                    $commitment = $offer->commitments()->create([
                        'name' => $validated['commitment_name']
                    ]);
                }

                if($commitment) {
                    //create arrangements
                    $arrangement = $commitment->arrangements()->create([
                        'name' => $validated['arrangement_name']
                    ]);

                    if($arrangement) {
                        //create commitment fields
                        $fieldsData = [];
                        foreach (OgpAreaArrangementFieldEnum::options()  as $key => $value) {
                            $fieldsData[] = [
                                'name' => $key,
                                'content' => $fields[$value] ?? '',
                                'is_system' => isset($fields[$value]),
                            ];
                        }
                        if($fieldsData) {
                            $arrangement->fields()->createMany($fieldsData);
                        }
                    }

                }

            } // offer

            return to_route('ogp.develop_new_action_plans.show', $item->id)
                ->with('success', trans_choice('custom.ogp_areas', 1)." ".__('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }

    }

    public function editOffer(Request $request, OgpAreaOffer $offer): View|\Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        if($user->cannot('update', $offer)) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }

        $ogpArea = $offer->area;


        return $this->view('site.ogp.offer.edit', compact('offer', 'ogpArea'));
    }

    public function storeComment(Request $request, OgpAreaOffer $offer): \Illuminate\Http\JsonResponse
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

    public function deleteComment(Request $request, OgpAreaOfferComment $comment): \Illuminate\Http\JsonResponse
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
        }
        catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'error' => 1,
                'message' => __('messages.system_error')
            ]);
        }
    }

}
