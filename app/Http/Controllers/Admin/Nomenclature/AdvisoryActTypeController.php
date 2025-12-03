<?php

namespace App\Http\Controllers\Admin\Nomenclature;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StoreAdvisoryActTypeRequest;
use App\Models\AdvisoryActType;
use App\Models\ConsultationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdvisoryActTypeController extends AdminController
{

    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $active = $request->get('active') ?? 1;
        $paginate = $filter['paginate'] ?? AdvisoryActType::PAGINATE;

        $items = AdvisoryActType::with(['translation'])
            ->FilterBy($requestFilter)
            ->whereActive($active)
            ->paginate($paginate);
        $toggleBooleanModel = 'AdvisoryActType';
        $editRouteName = 'admin.advisory-boards.nomenclature.advisory-act-type.edit';
        $listRouteName = 'admin.advisory-boards.nomenclature.advisory-act-type';

        return $this->view('admin.advisory-boards.nomenclatures.advisory-act-type.index', compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName'));
    }

    /**
     * @param Request $request
     * @param AdvisoryActType $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, AdvisoryActType $item)
    {
        if( ($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', AdvisoryActType::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = 'admin.advisory-boards.nomenclature.advisory-act-type.store';
        $listRouteName = 'admin.advisory-boards.nomenclature.advisory-act-type';
        $translatableFields = AdvisoryActType::translationFieldsProperties();
        $consultationLevels = ConsultationLevel::all();
        return $this->view('admin.advisory-boards.nomenclatures.advisory-act-type.edit', compact('item', 'storeRouteName', 'listRouteName', 'translatableFields', 'consultationLevels'));
    }

    public function store(StoreAdvisoryActTypeRequest $request, AdvisoryActType $item)
    {
        $id = $item->id;
        $validated = $request->validated();
        if( ($id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', AdvisoryActType::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $fillable['created_by'] = !$id ? $request->user()->id : null;
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(AdvisoryActType::TRANSLATABLE_FIELDS, $item, $validated);

            if( $id ) {
                return redirect(route('admin.advisory-boards.nomenclature.advisory-act-type.edit', $item) )
                    ->with('success', trans_choice('custom.nomenclature.advisory_act_type', 1)." ".__('messages.updated_successfully_m'));
            }

            return to_route('admin.advisory-boards.nomenclature.advisory-act-type')
                ->with('success', trans_choice('custom.nomenclature.advisory_act_type', 1)." ".__('messages.created_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
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
        $qItem = AdvisoryActType::query();
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
