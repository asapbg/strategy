<?php

namespace App\Http\Controllers\Admin\Nomenclature;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StoreConsultationDocumentTypeRequest;
use App\Models\ActType;
use App\Models\ConsultationDocumentType;
use App\Models\ConsultationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ConsultationDocumentTypeController extends AdminController
{
    const LIST_ROUTE = 'admin.nomenclature.consultation_document_type';
    const EDIT_ROUTE = 'admin.nomenclature.consultation_document_type.edit';
    const STORE_ROUTE = 'admin.nomenclature.consultation_document_type.store';
    const LIST_VIEW = 'admin.nomenclatures.consultation_document_type.index';
    const EDIT_VIEW = 'admin.nomenclatures.consultation_document_type.edit';

    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? ConsultationDocumentType::PAGINATE;

        $items = ConsultationDocumentType::with(['translation'])
            ->FilterBy($requestFilter)
            ->paginate($paginate);
        $toggleBooleanModel = 'ActType';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName'));
    }

    /**
     * @param Request $request
     * @param ConsultationDocumentType $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, ConsultationDocumentType $item)
    {
        if( ($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', ConsultationDocumentType::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = ConsultationDocumentType::translationFieldsProperties();
        $consultationLevels = ConsultationLevel::all();
        $actTypes = ActType::all();
        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields', 'consultationLevels', 'actTypes'));
    }

    public function store(StoreConsultationDocumentTypeRequest $request, ConsultationDocumentType $item)
    {
        $id = $item->id;
        $validated = $request->validated();
        if( ($id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', ConsultationDocumentType::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(ConsultationDocumentType::TRANSLATABLE_FIELDS, $item, $validated);

            if( $id ) {
                return redirect(route(self::EDIT_ROUTE, $item) )
                    ->with('success', trans_choice('custom.nomenclature.consultation_document_type', 1)." ".__('messages.updated_successfully_m'));
            }

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.nomenclature.consultation_document_type', 1)." ".__('messages.created_successfully_m'));
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
        $qItem = ConsultationDocumentType::query();
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
