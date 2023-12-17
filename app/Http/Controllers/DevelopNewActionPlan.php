<?php

namespace App\Http\Controllers;

use App\Enums\OgpAreaArrangementFieldEnum;
use App\Http\Requests\OgpAreaOfferRequest;
use App\Models\OgpArea;
use App\Models\OgpAreaArrangement;
use App\Models\OgpAreaCommitment;
use App\Models\OgpAreaOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
dd($validated);
        try {
            //TODO: if offer id is set dont create new offer
            if($offer_id) {
                $offer = OgpAreaOffer::findOrFail($offer_id);
            } else {
                //create new offer
                $offer = $item->offers()->create([
                    'users_id' => $user->id
                ]);
            }

            if($offer) {

                if(true) {
                    $commitment = OgpAreaCommitment::findOrFail($validated['commitment_id']);
                } else {
                    //create commitment
                    $commitment = $offer->commitments()->create([
                        'name' => $validated['commitment_name']
                    ]);
                }

                if($commitment) {

                    if($validated['arrangement_id']) {
                        $arrangement = OgpAreaArrangement::findOrFail($validated['arrangement_id']);
                    } else {
                        //create arrangements
                        $arrangement = $commitment->arrangements()->create([
                            'name' => $validated['arrangement_name']
                        ]);
                    }

                    if($arrangement) {
                        //create commitment fields
                        $fieldsData = [];
                        foreach (OgpAreaArrangementFieldEnum::options()  as $key => $value) {
                            $fieldsData[] = [
                                'name' => $key,
                                'content' => $value,
                                'is_system' => isset($fields[$value]),
                            ];
                        }
                        if($fieldsData) {
                            $fields = $arrangement->fields()->createMany($fieldsData);
                        }
                    }

                }

            }

            return to_route('ogp.develop_new_action_plans.show', $item->id)
                ->with('success', trans_choice('custom.ogp_areas', 1)." ".__('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }

    }


    public function editOffer(Request $request, OgpAreaOffer $offer)
    {
        $user = $request->user();

        if($user->cannot('update', $offer)) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }

        $ogpArea = $offer->area;


        return $this->view('site.ogp.offer.edit', compact('offer', 'ogpArea'));
    }
}
