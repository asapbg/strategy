<?php

namespace App\Http\Controllers\Admin\Ogp;

use App\Enums\DocTypesEnum;
use App\Enums\OgpStatusEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\OgpPlanArrangementEvaluationRequest;
use App\Http\Requests\OgpPlanArrangementRequest;
use App\Http\Requests\OgpPlanReportRequest;
use App\Http\Requests\OgpPlanRequest;
use App\Models\File;
use App\Models\OgpArea;
use App\Models\OgpPlan;
use App\Models\OgpPlanArea;
use App\Models\OgpPlanArrangement;
use App\Models\OgpStatus;
use App\Services\FileOcr;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Plans extends AdminController
{
    public function index(Request $request): \Illuminate\View\View
    {
        $name = ($request->filled('name')) ? $request->get('name') : null;
        $active = $request->filled('active') ? $request->get('active') : 1;
        $paginate = $request->filled('paginate') ? $request->get('paginate') : OgpPlan::PAGINATE;

        $items = OgpPlan::with(['status'])
            ->National()
            ->where('active', $active)
            ->when($name, function ($query, $name) {
                return $query->where('name', 'ILIKE', "%$name%");
            })
            ->paginate($paginate);

        return $this->view('admin.ogp_plan.index',
            compact('items', 'items')
        );
    }

    public function create(Request $request): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        return $this->edit($request);
    }

    public function edit(Request $request, $id = 0): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $evaluationEdit = false;
        $mainInfoEdit = true;
        $item = $id ? OgpPlan::find($id) : new OgpPlan();

        if($request->user()->cannot($id ? 'update' : 'create', $item)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        if($id && Carbon::parse($item->from_date)->format('Y-m-d') < Carbon::now()->format('Y-m-d')
            && $item->status->type == OgpStatusEnum::ACTIVE->value ) {
            $evaluationEdit = true;
        }

        if($id && $item->status->type == OgpStatusEnum::ACTIVE->value ) {
            $mainInfoEdit = false;
        }
        $translatableFields = \App\Models\OgpPlan::translationFieldsProperties();

        $ogpArea = OgpArea::Active()->get();
        $areas = $item->areas;

        return $this->view('admin.ogp_plan.'.($id ? 'edit' : "create"), compact('item', 'id', 'translatableFields', 'ogpArea', 'areas', 'evaluationEdit', 'mainInfoEdit'));
    }

    public function store(OgpPlanRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $id = $request->get('id');
        $item = $id ? OgpPlan::find($id) : new OgpPlan();

        if($request->user()->cannot($id ? 'update' : 'create', $item)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        //Edit only status
        if(isset($validated['save_status'])){
            if($id && isset($validated['status']) && $validated['status'] == OgpStatusEnum::ACTIVE->value
                && !dateBetween($item->from_date, $item->to_date)) {
                return back()->withInput()->with('warning', 'Планът не може да бъде \'Действащ\' извън срокът му на действие');
            }

            if($id && isset($validated['status']) && $validated['status'] == OgpStatusEnum::DRAFT->value
                && Carbon::parse($item->to_date)->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                return back()->withInput()->with('warning', 'Планът не може да бъде върнат в режим \'Чернова\'');
            }
            DB::beginTransaction();

            try {
                $item->ogp_status_id = $validated['status'];
                $item->save();

                DB::commit();
                return redirect(route('admin.ogp.plan.edit', ['id' => $item->id]))
                    ->with('success', trans_choice('custom.plans', 1)." ".__('messages.updated_successfully_m'));
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
            }

        }

        //Edit full main info
        if($id && isset($validated['status']) && $validated['status'] == OgpStatusEnum::ACTIVE->value
            && !dateBetween($validated['from_date'], $validated['to_date'])) {
            return back()->withInput()->with('warning', 'Планът не може да бъде \'Действащ\' извън срокът му на действие');
        }

        if($id && isset($validated['status']) && $validated['status'] == OgpStatusEnum::DRAFT->value
            && Carbon::parse($validated['to_date'])->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
            return back()->withInput()->with('warning', 'Планът не може да бъде върнат в режим \'Чернова\'');
        }

        if(!$id) {
            $validated['status'] = OgpStatus::Draft()->first()->id;
        }

        //TODO validate dates by Arrangement and other plans

        DB::beginTransaction();

        try {
            $validated['ogp_status_id'] = $validated['status'];
//            if(dateBetween($validated['from_date'], $validated['to_date'])){
//                $validated['ogp_status_id'] = OgpStatus::ActiveStatus()->first()->id;
//            } elseif(dateAfter($validated['from_date'])) {
//                $validated['ogp_status_id'] = OgpStatus::Draft()->first()->id;
//            }

//            if($id && dateAfter($validated['from_date'])){
//                $validated['ogp_status_id'] = OgpStatus::Draft()->first()->id;
//            }
//
            if(!$id) {
//                $validated['ogp_status_id'] = OgpStatus::Draft()->first()->id;
                $item->author_id = $request->user()->id;
            }

            $validated['national_plan'] = 1;
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(OgpPlan::TRANSLATABLE_FIELDS, $item, $validated);

            if(dateBetween($validated['from_date'], $validated['to_date'])){
                $route = route('admin.ogp.plan.index');
            } elseif(dateAfter($validated['from_date'])) {
                $route = route('admin.ogp.plan.edit', ['id' => $item->id]);
            } else{
                $route = route('admin.ogp.plan.edit', ['id' => $item->id]);
            }

            //add new area
            if(isset($validated['ogp_area']) && $validated['ogp_area']){
                $item->areas()->create([
                    'ogp_plan_id' => $item->id,
                    'ogp_area_id' => $validated['ogp_area']
                ]);
            }

            DB::commit();
            return redirect($route)
                ->with('success', trans_choice('custom.plans', 1)." ".__('messages.updated_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }

    }

    public function storeReport(OgpPlanReportRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $id = $validated['plan'];
        $item = OgpPlan::find($id);

        if($request->user()->cannot('update', $item)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        DB::beginTransaction();

        try {
            $this->storeTranslateOrNew(OgpPlan::TRANSLATABLE_FIELDS, $item, $validated);
            DB::commit();
            return redirect(route('admin.ogp.plan.edit', $item).'#report')
                ->with('success', trans_choice('custom.plans', 1)." ".__('messages.updated_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }

    }

    public function deleteArea(Request $request, OgpPlanArea $area)
    {
        $user = $request->user();
        if(!$area || !$area->id) {
            return back()->with('warning', __('messages.record_not_found'));
        }

        if($user->cannot('deleteArea', $area->plan)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $area->arrangements()->delete();
            $area->delete();
            return redirect(route('admin.ogp.plan.edit', ['id' => $area->plan->id]))->with('success', __('custom.the_record').' '.__('messages.deleted_successfully_m'));
        }
        catch (\Exception $e) {
            Log::error($e);
            return back()->with('warning', __('messages.system_error'));
        }
    }

    public function deleteArrangement(Request $request, OgpPlanArrangement $arrangement)
    {
        $user = $request->user();
        if(!$arrangement || !$arrangement->id) {
            return back()->with('warning', __('messages.record_not_found'));
        }

        if($user->cannot('deleteArea', $arrangement->ogpPlanArea->plan)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $arrangement->delete();
            return redirect(route('admin.ogp.plan.edit', $arrangement->ogpPlanArea->ogp_plan_id). '#area-tab-'. $arrangement->ogpPlanArea->id)
                ->with('success', __('custom.the_record').' '.__('messages.deleted_successfully_m'));
        }
        catch (\Exception $e) {
            Log::error($e);
            return back()->with('warning', __('messages.system_error'));
        }
    }

    public function destroy(Request $request, OgpPlan $plan): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        if($user->cannot('delete', $plan)) {
            return response()->json([
                'error' => 1,
                'message' => __('messages.no_rights_to_view_content')
            ]);
        }

        try {
            $plan->delete();
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

    public function addArea(Request $request, OgpPlan $plan)
    {
        $validator = Validator::make($request->all(), [
            'ogp_area' => 'required|gt:0',
        ]);

        if ($validator->fails()) {
            return to_route('admin.ogp.plan.edit', ['id' => $plan->id])
                ->withErrors($validator)
                ->withInput();
        }
        if($request->user()->cannot('update', $plan)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        DB::beginTransaction();

        try {

            //create ogp_plan_area
            $plan->areas()->create([
                'ogp_plan_id' => $plan->id,
                'ogp_area_id' => $request->get('ogp_area')
            ]);

            DB::commit();
            return to_route('admin.ogp.plan.edit', ['id' => $plan->id])
                ->with('success', trans_choice('custom.plans', 1)." ".__('messages.updated_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function orderArea(Request $request, OgpPlanArea $area)
    {
        if(!$area || !$area->id) {
            return back()->with('warning', __('messages.record_not_found'));
        }

        $validator = Validator::make($request->all(), [
            'ord' => 'required|numeric|gt:0',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.ogp.plan.edit', $area->ogp_plan_id). '#area-tab-'. $area->id)
                ->withErrors($validator)
                ->withInput();
        }

        if($request->user()->cannot('update', $area->plan)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();

        try {
            $validated = $validator->validated();
            $area->ord = $validated['ord'];
            $area->save();
            DB::commit();
            return redirect(route('admin.ogp.plan.edit', $area->ogp_plan_id). '#area-tab-'. $area->id)
                ->with('success', trans_choice('custom.plans', 1)." ".__('messages.updated_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function editArrangement(Request $request, OgpPlanArea $ogpPlanArea, $id = 0): \Illuminate\View\View
    {
        $translatableFields = \App\Models\OgpPlanArrangement::translationFieldsProperties();

        $item = $id ? OgpPlanArrangement::findOrFail($id) : new OgpPlanArrangement();

        $onlyEvaluationEdit = false;
        if(Carbon::parse($ogpPlanArea->plan->to_date)->format('Y-m-d') < Carbon::now()->format('Y-m-d')
            && $ogpPlanArea->plan->status->type == OgpStatusEnum::ACTIVE->value ) {
            $onlyEvaluationEdit = true;
        }

        return $this->view('admin.ogp_plan.new_arrangement', compact('ogpPlanArea', 'translatableFields', 'item', 'onlyEvaluationEdit'));
    }

    /**
     * @param OgpPlanArrangementRequest $request
     * @param OgpPlanArea $ogpPlanArea
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editArrangementStore(OgpPlanArrangementRequest $request, OgpPlanArea $ogpPlanArea): \Illuminate\Http\RedirectResponse
    {
        //TODO validate dates
        $validated = $request->validated();

        if($request->user()->cannot('update', $ogpPlanArea->plan)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        if(!dateBetween($ogpPlanArea->plan?->from_date, $ogpPlanArea->plan?->to_date, $validated['from_date']) || !dateBetween($ogpPlanArea->plan?->from_date, $ogpPlanArea->plan?->to_date, $validated['to_date'])){
            return back()->withInput()->with('warning', 'Срокът на мярката трябва да е част от срокът в който планът ще бъде дейтсващ.');
        }

        $id = (int)$validated['id'];
        DB::beginTransaction();

        try {

            if($id) {
                $opa = OgpPlanArrangement::find($id);
            } else{
                $opa = new OgpPlanArrangement();
                $opa->ogp_plan_area_id = $ogpPlanArea->id;
            }

            $fillable = $this->getFillableValidated($validated, $opa);
            $opa->fill($fillable);
            $opa->save();
            $this->storeTranslateOrNew(OgpPlanArrangement::TRANSLATABLE_FIELDS, $opa, $validated);

            DB::commit();
            return redirect( route('admin.ogp.plan.edit', $ogpPlanArea->ogp_plan_id). '#area-tab-'. $ogpPlanArea->id)
                ->with('success', trans_choice('custom.plans', 1)." ".__('messages.updated_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function editArrangementEvaluation(Request $request, OgpPlanArea $ogpPlanArea, $id): \Illuminate\View\View
    {
        $translatableFields = \App\Models\OgpPlanArrangement::translationFieldsProperties();
        $item = $id ? OgpPlanArrangement::findOrFail($id) : new OgpPlanArrangement();

        return $this->view('admin.ogp_plan.arrangement_evaluation', compact('ogpPlanArea', 'translatableFields', 'item'));
    }

    public function editArrangementEvaluationStore(OgpPlanArrangementEvaluationRequest $request, OgpPlanArea $ogpPlanArea): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        if($request->user()->cannot('update', $ogpPlanArea->plan)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $id = (int)$validated['id'];
        DB::beginTransaction();

        try {
            $opa = OgpPlanArrangement::find($id);
            $this->storeTranslateOrNew(OgpPlanArrangement::TRANSLATABLE_FIELDS, $opa, $validated);

            DB::commit();
            return redirect( route('admin.ogp.plan.edit', $ogpPlanArea->ogp_plan_id). '#area-tab-'. $ogpPlanArea->id)
                ->with('success', trans_choice('custom.plans', 1)." ".__('messages.updated_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }
}
