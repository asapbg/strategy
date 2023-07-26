<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StorePCSubjectRequest;
use App\Models\PolicyArea;
use App\Models\PCSubject;
use App\Models\AuthorityAcceptingStrategic;
use App\Models\StrategicActType;
use App\Models\PCSubjectLevel;
use App\Models\PCSubjectType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PCSubjectController extends AdminController
{
    const LIST_ROUTE = 'admin.pc_subjects.index';
    const EDIT_ROUTE = 'admin.pc_subjects.edit';
    const STORE_ROUTE = 'admin.pc_subjects.store';
    const LIST_VIEW = 'admin.pc_subjects.index';
    const EDIT_VIEW = 'admin.pc_subjects.edit';

    /**
     * Show the public consultations.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? PCSubject::PAGINATE;

        $items = PCSubject::with(['translation'])
            ->FilterBy($requestFilter)
            ->paginate($paginate);
        $toggleBooleanModel = 'PCSubject';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $types = PCSubject::getTypes();

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName', 'types'));
    }

    /**
     * @param Request $request
     * @param PCSubject $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $item = null)
    {
        $item = $this->getRecord($item);
        if( ($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', PCSubject::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = PCSubject::translationFieldsProperties();
        $types = PCSubject::getTypes();
        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields', 'types'));
    }

    public function store(StorePCSubjectRequest $request, $item = null)
    {
        $item = $this->getRecord($item);
        $validated = $request->validated();
        if( ($item->id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', PCSubject::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNewCurrent(PCSubject::TRANSLATABLE_FIELDS, $item, $validated);

            if( $item->id ) {
                return redirect(route(self::EDIT_ROUTE, $item) )
                    ->with('success', trans_choice('custom.public_consultations', 1)." ".__('messages.updated_successfully_m'));
            }

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.public_consultation', 1)." ".__('messages.created_successfully_m'));
        } catch (\Exception $e) {
            dd($e, $validated);
            \Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }

    }

    private function filters($request)
    {
        return array(
            'contractor' => array(
                'type' => 'text',
                'placeholder' => __('custom.contractor'),
                'value' => $request->input('contractor'),
                'col' => 'col-md-4'
            ),
            'executor' => array(
                'type' => 'text',
                'placeholder' => __('custom.executor'),
                'value' => $request->input('executor'),
                'col' => 'col-md-4'
            ),
            'type' => array(
                'type' => 'select',
                'value' => $request->input('type'),
                'options' => PCSubject::getTypes()->map(function($item, $key) {
                    return ['value' => $key, 'name' => __($item)];
                }),
                'col' => 'col-md-4'
            ),
        );
    }

    /**
     * @param $id
     * @param array $with
     */
    private function getRecord($id, array $with = []): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $qItem = PCSubject::query();
        if( sizeof($with) ) {
            $qItem->with($with);
        }
        $item = $qItem->find((int)$id);
        if( !$item ) {
            return new PCSubject();
        }
        return $item;
    }
}
