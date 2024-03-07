<?php

namespace App\Http\Controllers\Admin\Ogp;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\OgpPlanArrangementActionStoreRequest;
use App\Models\OgpPlanArrangement;
use App\Models\OgpPlanArrangementAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OgpPlanArrangementActionController extends AdminController
{
    public function store(OgpPlanArrangementActionStoreRequest $request)
    {
        $rawValidated = $request->validated();
        $id = (int)$rawValidated['id'];
        $arrangement = OgpPlanArrangement::find($rawValidated['ogp_plan_arrangement_id']);

        if(!$arrangement){
            return back()->with('warning', __('messages.unauthorized'));
        }

        if($request->user()->cannot('update', $arrangement->ogpPlanArea->plan)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            if($id){
                $item = OgpPlanArrangementAction::find($id);
            } else{
                $item = new OgpPlanArrangementAction();
                $item->ogp_plan_arrangement_id = $arrangement->id;
            }

            $validated = [];
            foreach ($rawValidated as $k => $f){
                $validated[str_replace('new_', '', $k)] = $f;
            }

            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(OgpPlanArrangementAction::TRANSLATABLE_FIELDS, $item, $validated);

            DB::commit();
            return redirect( route('admin.ogp.plan.arrangement.edit', ['ogpPlanArea' => $arrangement->ogpPlanArea->id, 'id' => $arrangement->id]))
                ->with('success', trans_choice('custom.arrangement', 1)." ".__('messages.updated_successfully_f'));

        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'numeric'],
            'from_date' => ['required', 'date', 'before:to_date'],
            'to_date' => ['required', 'date'],
            'name_bg' => ['required', 'string', 'max:2000'],
            'name_en' => ['required', 'string', 'max:2000'],
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 200);
        }

        $validated = $validator->validated();
        $item = OgpPlanArrangementAction::find((int)$validated['id']);
        if(!$item){
            return response()->json(['main_error' => __('messages.records_not_found')], 200);
        }

        if($request->user()->cannot('update', $item->arrangement->ogpPlanArea->plan)) {
            return response()->json(['main_error' => __('messages.unauthorized')], 200);
        }

        DB::beginTransaction();
        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();
            $item->translateOrNew('bg')->name = $validated['name_bg'];
            $item->translateOrNew('en')->name = $validated['name_en'];
            $item->save();

            DB::commit();
            return response()->json(['message' => __('custom.the_record'). ' ' .__('messages.updated_successfully_m')], 200);

        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }
}
