<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StoreLegislativeInitiativeRequest;
use App\Models\LegislativeInitiative;
use App\Models\RegulatoryAct;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegislativeInitiativeController extends AdminController
{
    const LIST_ROUTE = 'admin.legislative_initiatives.index';
    const EDIT_ROUTE = 'admin.legislative_initiatives.edit';
    const STORE_ROUTE = 'admin.legislative_initiatives.store';
    const LIST_VIEW = 'admin.legislative_initiatives.index';
    const EDIT_VIEW = 'admin.legislative_initiatives.edit';

    /**
     * Show the public consultations.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? LegislativeInitiative::PAGINATE;

        $items = LegislativeInitiative::with(['translation'])
            ->FilterBy($requestFilter)
            ->paginate($paginate);
        $toggleBooleanModel = 'LegislativeInitiative';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName'));
    }

    /**
     * @param Request $request
     * @param LegislativeInitiative $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $item = null)
    {
        $item = $this->getRecord($item);
        if( ($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', LegislativeInitiative::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = LegislativeInitiative::translationFieldsProperties();
        
        $regulatoryActs = RegulatoryAct::all();
        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields', 'regulatoryActs'));
    }

    public function store(StoreLegislativeInitiativeRequest $request, $item = null)
    {
        $item = $this->getRecord($item);
        $validated = $request->validated();
        if( ($item->id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', LegislativeInitiative::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            if ($item->id) {
                if ($request->input('deleted')) {
                    $item->deleteTranslations();
                    $item->delete();
                }
                else if ($item->deleted_at) {
                    $item->restore();
                }
            }
            $item->save();
            $this->storeTranslateOrNewCurrent(LegislativeInitiative::TRANSLATABLE_FIELDS, $item, $validated);

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
            'title' => array(
                'type' => 'text',
                'placeholder' => __('validation.attributes.title'),
                'value' => $request->input('title'),
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
        $qItem = LegislativeInitiative::withTrashed();
        if( sizeof($with) ) {
            $qItem->with($with);
        }
        $item = $qItem->find((int)$id);
        if( !$item ) {
            return new LegislativeInitiative();
        }
        return $item;
    }
}
