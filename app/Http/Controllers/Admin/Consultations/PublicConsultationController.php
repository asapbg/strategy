<?php

namespace App\Http\Controllers\Admin\Consultations;

use App\Enums\DynamicStructureTypesEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StorePublicConsultationRequest;
use App\Models\ActType;
use App\Models\ConsultationLevel;
use App\Models\Consultations\LegislativeProgram;
use App\Models\Consultations\OperationalProgram;
use App\Models\Consultations\PublicConsultation;
use App\Models\ConsultationType;
use App\Models\DynamicStructure;
use App\Models\DynamicStructureColumn;
use App\Models\LinkCategory;
use App\Models\ProgramProject;
use App\Models\RegulatoryAct;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicConsultationController extends AdminController
{
    const LIST_ROUTE = 'admin.consultations.public_consultations.index';
    const EDIT_ROUTE = 'admin.consultations.public_consultations.edit';
    const STORE_ROUTE = 'admin.consultations.public_consultations.store';
    const LIST_VIEW = 'admin.consultations.public_consultations.index';
    const EDIT_VIEW = 'admin.consultations.public_consultations.edit';

    /**
     * Show the public consultations.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? PublicConsultation::PAGINATE;

        $items = PublicConsultation::with(['translation'])
            ->FilterBy($requestFilter)
            ->paginate($paginate);
        $toggleBooleanModel = 'PublicConsultation';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName'));
    }

    /**
     * @param Request $request
     * @param PublicConsultation|null $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, PublicConsultation|null $item)
    {
        if( ($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', PublicConsultation::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $kdRowsDB = $item->id ?
            DynamicStructureColumn::whereIn('id', json_decode($item->active_columns))->orderBy('id')->get()
            : DynamicStructure::where('type', '=', DynamicStructureTypesEnum::CONSULT_DOCUMENTS->value)->where('active', '=', 1)->first()->columns;

        $kdRows = $kdRowsDB;
        foreach ($kdRowsDB as $row) {
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = PublicConsultation::translationFieldsProperties();

        $consultationTypes = ConsultationType::all();
        $consultationLevels = ConsultationLevel::all();
        $actTypes = ActType::with(['consultationLevel'])->get();
        $programProjects = ProgramProject::all();
        $linkCategories = LinkCategory::all();
        $regulatoryActs = RegulatoryAct::all(); //Нормативни актове номенклатура
        $prisActs = null; //TODO fix me Add them after PRIS module
        $operationalPrograms = OperationalProgram::get(); //TODO get only not in use and current if item
        $legislativePrograms = LegislativeProgram::get(); //TODO get only not in use and current if item
        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields',
            'consultationTypes', 'consultationLevels', 'actTypes', 'programProjects', 'linkCategories', 'regulatoryActs', 'prisActs',
            'operationalPrograms', 'legislativePrograms', 'kdRows'));
    }

    public function store(StorePublicConsultationRequest $request, PublicConsultation $item)
    {
        $id = $item->id;
        $validated = $request->validated();
        if( ($id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', PublicConsultation::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->active = $request->input('active') ? 1 : 0;
            $item->save();
            $this->storeTranslateOrNewCurrent(PublicConsultation::TRANSLATABLE_FIELDS, $item, $validated);

            if( $id ) {
                return redirect(route(self::EDIT_ROUTE, $item) )
                    ->with('success', trans_choice('custom.public_consultations', 1)." ".__('messages.updated_successfully_m'));
            }

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.public_consultation', 1)." ".__('messages.created_successfully_m'));
        } catch (\Exception $e) {
            \Log::error($e);
            dd($e, $validated);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }

    }

    private function filters($request)
    {
        return array(
            'name' => array(
                'type' => 'text',
                'placeholder' => __('validation.attributes.name'),
                'value' => $request->input('name'),
                'col' => 'col-md-4'
            )
        );
    }

    /**
     * @param $id
     * @param array $with
     */
    private function getRecord($id, array $with = []): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $qItem = PublicConsultation::query();
        if( sizeof($with) ) {
            $qItem->with($with);
        }
        $item = $qItem->find((int)$id);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        return $item;
    }
}
