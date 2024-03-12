<?php

namespace App\Http\Controllers\Admin\Ogp;

use App\Enums\OgpStatusEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\OgpPlanScheduleStoreRequest;
use App\Models\OgpPlan;
use App\Models\OgpPlanSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OgpPlanScheduleController extends AdminController
{
    public function index(Request $request, OgpPlan $plan): \Illuminate\View\View
    {
        $canEditSchedule = $canCreateSchedule = $canDeleteSchedule = !($plan->status->type == OgpStatusEnum::ACTIVE->value);
        $items = $plan->schedules()->orderBy('start_date','asc')->paginate(OgpPlanSchedule::PAGINATE);
        $areas = $plan->areas;
        return $this->view('admin.ogp_develop_plan.tab.schedule',
            compact('plan', 'areas', 'items', 'canCreateSchedule', 'canEditSchedule', 'canDeleteSchedule')
        );
    }

    public function store(Request $request){
        $r = new OgpPlanScheduleStoreRequest();
        $validator = Validator::make($request->all(), $r->rules());

        if($validator->fails()){
            if($request->ajax()){
                return response()->json(['errors' => $validator->errors()], 200);
            } else{
                return back()->withInput()->withErrors($validator->errors())->with('danger', __('messages.check_for_errors'));
            }
        }

        $validated = $validator->validated();

        $id = $validated['id'];
        $plan = OgpPlan::find($validated['plan']);

        if($request->user()->cannot('updateDevelopPlan', $plan)){
            if($request->ajax()){
                return response()->json(['main_error' => __('messages.unauthorized')], 200);
            } else{
                return back()->with('warning', __('messages.unauthorized'));
            }
        }

        if($id){
            $item = $plan->schedules()->where('id', '=', $id)->first();
            if(!$item){
                if($request->ajax()){
                    return response()->json(['main_error' => __('messages.record_not_found')], 200);
                } else{
                    return back()->with('warning', __('messages.record_not_found'));
                }
            }
        } else{
            $item = new OgpPlanSchedule();
            $item->ogp_plan_id = $plan->id;
        }

        \DB::beginTransaction();
        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(OgpPlanSchedule::TRANSLATABLE_FIELDS, $item, $validated);
            \DB::commit();

            if($request->ajax()){
                return response()->json(['success_message' => trans_choice('custom.events', 1). ' ' .__('messages.updated_successfully_n')], 200);
            } else{
                return redirect(route('admin.ogp.plan.develop.schedule', $plan));
            }

        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Store ogp plan schedule element error: '.$e);
            if($request->ajax()){
                return response()->json(['main_error' => __('messages.system_error')], 200);
            } else{
                return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
            }
        }
    }

    public function destroy(Request $request, OgpPlanSchedule $schedule)
    {
        $user = $request->user();

        if(!$schedule->id){
            return back()->with('warnig', __('messages.records_not_found'));
        }

        if($user->cannot('updateDevelopPlan', $schedule->plan)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            $schedule->delete();
            DB::commit();

            return redirect(route('admin.ogp.plan.develop.schedule', $schedule->plan))
                ->with('success', trans_choice('custom.events', 1)." ".__('messages.deleted_successfully_n'));
        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete ogp plan event error: '.$e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }
}
