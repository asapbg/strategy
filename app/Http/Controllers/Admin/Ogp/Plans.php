<?php

namespace App\Http\Controllers\Admin\Ogp;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\OgpPlanArrangementRequest;
use App\Http\Requests\OgpPlanRequest;
use App\Models\OgpArea;
use App\Models\OgpPlan;
use App\Models\OgpPlanArea;
use App\Models\OgpPlanArrangement;
use App\Models\OgpStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Plans extends AdminController
{
    public function index(Request $request): \Illuminate\View\View
    {
        $name = ($request->filled('name')) ? $request->get('name') : null;
        $active = $request->filled('active') ? $request->get('active') : 1;
        $paginate = $request->filled('paginate') ? $request->get('paginate') : OgpPlan::PAGINATE;

        $items = OgpPlan::with(['status'])
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
        $item = $id ? OgpPlan::find($id) : new OgpPlan();

        if($request->user()->cannot($id ? 'update' : 'create', $item)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        $translatableFields = \App\Models\OgpPlan::translationFieldsProperties();

        $ogpArea = OgpArea::get();
        $areas = $item->areas;

        return $this->view('admin.ogp_plan.'.($id ? 'edit' : "create"), compact('item', 'id', 'translatableFields', 'ogpArea', 'areas'));
    }

    public function store(OgpPlanRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $id = $request->get('id');
        $item = $id ? OgpPlan::find($id) : new OgpPlan();

        if($request->user()->cannot($id ? 'update' : 'create', $item)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        DB::beginTransaction();

        try {
            if($id == 0) {
                $item->author_id = $request->user()->id;
                $item->ogp_status_id = OgpStatus::Draft()->first()->id;
            } else {
                $item->ogp_status_id = $request->get('status');
            }
                $item->from_date = Carbon::parse($validated['from_date'])->format('Y-m-d');
                $item->to_date = Carbon::parse($validated['to_date'])->format('Y-m-d');

            $item->save();

            $this->storeTranslateOrNew(OgpPlan::TRANSLATABLE_FIELDS, $item, $validated);

            if($id == 0) {
                //create ogp_plan_area
                $item->areas()->create([
                    'ogp_plan_id' => $item->id,
                    'ogp_area_id' => $validated['ogp_area']
                ]);
            }

            DB::commit();
            return to_route('admin.ogp.plan.edit', ['id' => $item->id])
                ->with('success', trans_choice('custom.plans', 1)." ".__('messages.updated_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
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
            return to_route('admin.ogp.plan.edit', $plan->id)
                ->withErrors($validator)
                ->withInput();;
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

    public function editArrangement(Request $request, OgpPlanArea $ogpPlanArea, $id = 0): \Illuminate\View\View
    {
        $translatableFields = \App\Models\OgpPlanArrangement::translationFieldsProperties();

        $item = $id ? OgpPlanArrangement::findOrFail($id) : new OgpPlanArrangement();

        return $this->view('admin.ogp_plan.new_arrangement', compact('ogpPlanArea', 'translatableFields', 'item'));
    }

    /**
     * @param OgpPlanArrangementRequest $request
     * @param OgpPlanArea $ogpPlanArea
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editArrangementStore(OgpPlanArrangementRequest $request, OgpPlanArea $ogpPlanArea): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {

            $opa = new OgpPlanArrangement();
            $opa->ogp_plan_area_id = $ogpPlanArea->id;
            foreach ($validated as $field => $value) {
                if((!is_null($value) || !empty($value)) && in_array($field, ['from_date', 'to_date']) ) {
                    $opa->{$field} = Carbon::parse($value)->format('Y-m-d');
                }
            }
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
}
