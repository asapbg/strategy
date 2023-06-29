<?php

namespace App\Http\Controllers\Admin\Nomenclature;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StoreStrategicDocumentTypeRequest;
use App\Models\StrategicDocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class StrategicDocumentTypeController extends AdminController
{
    const LIST_ROUTE = 'admin.nomenclature.strategic_document_type';
    const EDIT_ROUTE = 'admin.nomenclature.strategic_document_type.edit';
    const STORE_ROUTE = 'admin.nomenclature.strategic_document_type.store';
    const LIST_VIEW = 'admin.nomenclatures.strategic_document_type.index';
    const EDIT_VIEW = 'admin.nomenclatures.strategic_document_type.edit';

    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? StrategicDocumentType::PAGINATE;

        $items = StrategicDocumentType::with(['translation'])
            ->FilterBy($requestFilter)
            ->paginate($paginate);
        $toggleBooleanModel = 'StrategicDocumentType';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName'));
    }

    /**
     * @param Request $request
     * @param StrategicDocumentType $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, StrategicDocumentType $item)
    {
        if( ($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', StrategicDocumentType::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = StrategicDocumentType::translationFieldsProperties();
        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields'));
    }

    public function store(StoreStrategicDocumentTypeRequest $request, StrategicDocumentType $item)
    {
        $id = $item->id;
        $validated = $request->validated();
        if( ($id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', StrategicDocumentType::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(StrategicDocumentType::TRANSLATABLE_FIELDS, $item, $validated);

            if( $id ) {
                return redirect(route(self::EDIT_ROUTE, $item) )
                    ->with('success', trans_choice('custom.nomenclature.strategic_document_type', 1)." ".__('messages.updated_successfully_m'));
            }

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.nomenclature.strategic_document_type', 1)." ".__('messages.created_successfully_m'));
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
        $qItem = StrategicDocumentType::query();
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
