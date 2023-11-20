<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreLegislativeInitiativeRequest;
use App\Models\LegislativeInitiative;
use App\Models\RegulatoryAct;
use App\Models\RegulatoryActType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class LegislativeInitiativeController extends AdminController
{
    const LIST_ROUTE = 'admin.legislative_initiatives.index';
    const EDIT_ROUTE = 'admin.legislative_initiatives.edit';
    const STORE_ROUTE = 'admin.legislative_initiatives.store';
    const LIST_VIEW = 'admin.legislative_initiatives.index';
    const EDIT_VIEW = 'admin.legislative_initiatives.edit';
    const CREATE_VIEW = 'admin.legislative_initiatives.create';

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->title_singular = 'Законодателна инициатива';
    }

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

        $items = LegislativeInitiative::withTrashed()->with(['translation'])
            ->FilterBy($requestFilter)
            ->paginate($paginate);
        $toggleBooleanModel = 'LegislativeInitiative';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName'));
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
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $regulatoryActTypes = RegulatoryActType::orderBy('id')->get();
        $translatableFields = LegislativeInitiative::translationFieldsProperties();
        $item = new LegislativeInitiative();

        return $this->view(self::CREATE_VIEW, compact('regulatoryActTypes', 'translatableFields', 'item'));
    }

    /**
     * @param Request               $request
     * @param LegislativeInitiative $item
     *
     * @return View
     */
    public function edit(Request $request, LegislativeInitiative $item): View
    {
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = LegislativeInitiative::translationFieldsProperties();
        $regulatoryActs = RegulatoryAct::all();

        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields', 'regulatoryActs'));
    }

    public function store(StoreLegislativeInitiativeRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $new = new LegislativeInitiative();

            $fillable = $this->getFillableValidated($validated, $new);
            $new->fill($fillable);
            $new->save();

            $this->storeTranslateOrNew(LegislativeInitiative::TRANSLATABLE_FIELDS, $new, $validated);

            DB::commit();

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.legislative_initiatives', 1) . " " . __('messages.created_successfully_m'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function update(StoreLegislativeInitiativeRequest $request, LegislativeInitiative $item)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();

            $this->storeTranslateOrNew(LegislativeInitiative::TRANSLATABLE_FIELDS, $item, $validated);

            DB::commit();

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.legislative_initiatives', 1) . " " . __('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function destroy(LegislativeInitiative $item)
    {
        try {
            $item->delete();

            return redirect(route(self::LIST_ROUTE, $item))
                ->with('success', trans_choice('custom.legislative_initiatives', 1) . " " . __('messages.deleted_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect(route(self::LIST_ROUTE, $item))->with('danger', __('messages.system_error'));
        }
    }

    public function restore(LegislativeInitiative $item)
    {
        try {
            $item->restore();

            return redirect(route(self::LIST_ROUTE, $item))
                ->with('success', trans_choice('custom.legislative_initiatives', 1) . " " . __('messages.restored_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect(route(self::LIST_ROUTE, $item))->with('danger', __('messages.system_error'));
        }
    }
}
