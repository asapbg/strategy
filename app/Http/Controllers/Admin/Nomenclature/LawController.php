<?php

namespace App\Http\Controllers\Admin\Nomenclature;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Requests\LawStoreRequest;
use App\Models\Law;
use App\Models\StrategicDocuments\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LawController extends AdminController
{
    const LIST_ROUTE = 'admin.nomenclature.law';
    const EDIT_ROUTE = 'admin.nomenclature.law.edit';
    const STORE_ROUTE = 'admin.nomenclature.law.store';
    const LIST_VIEW = 'admin.nomenclatures.law.index';
    const EDIT_VIEW = 'admin.nomenclatures.law.edit';

    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        if( !$request->filled('active') ) {
            $filter['active']['value'] = 1;
            $requestFilter['active'] = 1;
        }

        $paginate = $filter['paginate'] ?? Law::PAGINATE;

        $items = Law::with(['translation', 'institutions', 'institutions.translations'])
            ->FilterBy($requestFilter)
            ->orderByTranslation('name')
            ->paginate($paginate);
        $toggleBooleanModel = 'Law';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName'));
    }

    /**
     * @param Request $request
     * @param Law $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, Law $item)
    {
        if( ($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', Law::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = Law::translationFieldsProperties();
        $institutions = Institution::optionsListWithAttr();
        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields', 'institutions'));
    }

    public function store(LawStoreRequest $request, Law $item)
    {
        $id = $item->id;
        $validated = $request->validated();
        if( ($id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', Law::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();
            $item->institutions()->sync($validated['institution_id'] ?? []);
            $this->storeTranslateOrNew(Law::TRANSLATABLE_FIELDS, $item, $validated);

            if( $id ) {
                return redirect(route(self::EDIT_ROUTE, $item) )
                    ->with('success', trans_choice('custom.nomenclature.laws', 1)." ".__('messages.updated_successfully_m'));
            }

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.nomenclature.laws', 1)." ".__('messages.created_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }

    }

    private function filters($request)
    {
        return array(
            'title' => array(
                'type' => 'text',
                'placeholder' => __('validation.attributes.label'),
                'value' => $request->input('title'),
                'col' => 'col-md-4'
            ),
            'active' => array(
                'type' => 'select',
                'options' => optionsStatusesFilter(true, '', __('custom.status').' ('.__('custom.any').')'),
                'default' => '',
                'placeholder' => __('validation.attributes.status'),
                'value' => $request->input('active'),
                'col' => 'col-md-2 d-none',
            )
        );
    }

    /**
     * @param $id
     * @param array $with
     */
    private function getRecord($id, array $with = []): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $qItem = Law::query();
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
