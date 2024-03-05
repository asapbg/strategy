<?php

namespace App\Http\Controllers\Admin\Ogp;

use App\Enums\DocTypesEnum;
use App\Enums\OgpStatusEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\OgpDevelopPlanRequest;
use App\Http\Requests\OgpPlanArrangementRequest;
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

class DevelopNewPlanController extends AdminController
{
    public function index(Request $request): \Illuminate\View\View
    {
        $name = ($request->filled('name')) ? $request->get('name') : null;
        $active = $request->filled('active') ? $request->get('active') : 1;
        $paginate = $request->filled('paginate') ? $request->get('paginate') : OgpPlan::PAGINATE;

        $items = OgpPlan::select('ogp_plan.*')->with(['translations', 'status'])
            ->join('ogp_status', 'ogp_status.id', '=', 'ogp_plan.ogp_status_id')
            ->leftJoin('ogp_plan_translations', function ($j){
                $j->on('ogp_plan_translations.ogp_plan_id' ,'=', 'ogp_plan.id')->where('ogp_plan_translations.locale', '=', app()->getLocale());
            })
            ->whereIn('ogp_status.type', [OgpStatusEnum::IN_DEVELOPMENT->value, OgpStatusEnum::DRAFT->value, OgpStatusEnum::FINAL->value])
            ->where('ogp_plan.active', $active)
            ->where('ogp_plan.national_plan', 0)
            ->when($name, function ($query, $name) {
                return $query->where('ogp_plan_translations.name', 'ilike', "%$name%");
            })
            ->paginate($paginate);
        return $this->view('admin.ogp_develop_plan.index',
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

        if($request->user()->cannot($id ? 'updateDevelopPlan' : 'createDevelopPlan', $item)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        $translatableFields = \App\Models\OgpPlan::translationFieldsProperties();

        $ogpArea = OgpArea::Active()->get();
        $areas = $item->areas;

        return $this->view('admin.ogp_develop_plan.'.($id ? 'edit' : "create"), compact('item', 'id', 'translatableFields', 'ogpArea', 'areas'));
    }

    public function show(Request $request, OgpPlan $plan): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $item = $plan;
        if($request->user()->cannot('viewDevelopPlan', $item)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $translatableFields = \App\Models\OgpPlan::translationFieldsProperties();

        $areas = $item->areas;

        return $this->view('admin.ogp_develop_plan.show', compact('item', 'translatableFields', 'areas'));
    }

    public function store(OgpDevelopPlanRequest $request): \Illuminate\Http\RedirectResponse
    {
        $needToGeneratePdf = false;
        $validated = $request->validated();
        $id = $request->get('id');
        $item = $id ? OgpPlan::find($id) : new OgpPlan();

        if($request->user()->cannot($id ? 'updateDevelopPlan' : 'createDevelopPlan', $item)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        DB::beginTransaction();

        try {
            if(dateBetween($validated['from_date_develop'], $validated['to_date_develop'])){
                if(!$id) {
                    $validated['ogp_status_id'] = OgpStatus::InDevelopment()->first()->id;
                }
            } elseif(dateAfter($validated['from_date_develop'])) {
                if(!$id){
                    $validated['ogp_status_id'] = OgpStatus::Draft()->first()->id;
                }
            }

            if($id) {
                if($item->ogp_status_id != $validated['ogp_status_id'] && $validated['ogp_status_id'] == OgpStatus::Final()->first()->id){
                    $needToGeneratePdf = true;
                }
            } else{
                $item->author_id = $request->user()->id;
            }

            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(OgpPlan::TRANSLATABLE_FIELDS, $item, $validated);

            //add new area
            if(isset($validated['ogp_area']) && $validated['ogp_area']){
                $item->areas()->create([
                    'ogp_plan_id' => $item->id,
                    'ogp_area_id' => $validated['ogp_area']
                ]);
            }

            $route = route('admin.ogp.plan.develop.edit', ['id' => $item->id]);
            if($needToGeneratePdf) {
                $exportData = [
                    'title' => $item->name,
                    'content' => $item->content,
                    'rows' => $item->areas
                ];
                $path = File::OGP_PLAN_UPLOAD_DIR.$item->id.DIRECTORY_SEPARATOR;
                $fileName = 'version_after_consultation_'.$item->id;

                $pdf = PDF::loadView('exports.ogp_plan', ['data' => $exportData, 'isPdf' => true]);
                Storage::disk('public_uploads')->put($path.$fileName.'.pdf', $pdf->output());

                //Attach files to public consultation
                foreach (config('available_languages') as $lang) {
                    $pdfFile = new File([
                        'id_object' => $item->id,
                        'code_object' => File::CODE_OBJ_OGP,
                        'doc_type' => DocTypesEnum::OGP_VERSION_AFTER_CONSULTATION,
                        'filename' => $fileName.'.pdf',
                        'content_type' => 'application/pdf',
                        'path' => $path.$fileName.'.pdf',
                        'description_'.$lang['code'] => __('custom.ogp.doc_type.'.DocTypesEnum::OGP_VERSION_AFTER_CONSULTATION->value),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'locale' => $lang['code'],
                        'version' => '1.0',
                    ]);
                    $pdfFile->save();
                    $ocr = new FileOcr($pdfFile->refresh());
                    $ocr->extractText();
                }
                $route = route('admin.ogp.plan.develop.view', $item);
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

    public function delete(Request $request, OgpPlan $plan)
    {
        $user = $request->user();
        if($user->cannot('deleteDevelopPlan', $plan)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $plan->delete();
            return redirect(route('admin.ogp.plan.develop.index'))->with('success', __('custom.the_record').' '.__('messages.deleted_successfully_m'));
        }
        catch (\Exception $e) {
            Log::error($e);
            return back()->with('warning', __('messages.system_error'));
        }
    }

//    public function destroy(Request $request, OgpPlan $plan): \Illuminate\Http\JsonResponse
//    {
//        $user = $request->user();
//        if($user->cannot('deleteDevelopPlan', $plan)) {
//            return response()->json([
//                'error' => 1,
//                'message' => __('messages.no_rights_to_view_content')
//            ]);
//        }
//
//        try {
//            $plan->delete();
//            return response()->json([
//                'error' => 0,
//                'row_id' => $request->get('row_id')
//            ]);
//        }
//        catch (\Exception $e) {
//            Log::error($e);
//
//            return response()->json([
//                'error' => 1,
//                'message' => __('messages.system_error')
//            ]);
//        }
//    }

    public function addArea(Request $request, OgpPlan $plan)
    {
        $validator = Validator::make($request->all(), [
            'ogp_area' => 'required|exists:ogp_area,id',
        ]);

        if ($validator->fails()) {
            return to_route('admin.ogp.plan.develop.edit', $plan->id)
                ->withErrors($validator)
                ->withInput();;
        }

        if($request->user()->cannot('updateDevelopPlan', $plan)) {
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
            return to_route('admin.ogp.plan.develop.edit', ['id' => $plan->id])
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

        return $this->view('admin.ogp_develop_plan.new_arrangement', compact('ogpPlanArea', 'translatableFields', 'item'));
    }

    /**
     * @param OgpPlanArrangementRequest $request
     * @param OgpPlanArea $ogpPlanArea
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editArrangementStore(OgpPlanArrangementRequest $request, OgpPlanArea $ogpPlanArea): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
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
            return redirect( route('admin.ogp.plan.develop.edit', $ogpPlanArea->ogp_plan_id). '#area-tab-'. $ogpPlanArea->id)
                ->with('success', trans_choice('custom.plans', 1)." ".__('messages.updated_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }
}
