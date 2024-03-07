<?php

namespace App\Http\Controllers\Admin\Ogp;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\OgpPlanArrangementActionStoreRequest;
use App\Models\OgpPlanArrangement;
use App\Models\OgpPlanArrangementAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
}
