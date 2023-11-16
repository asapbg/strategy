<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreLegislativeInitiativeRequest;
use App\Models\LegislativeInitiative;
use App\Models\RegulatoryAct;
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

    /**
     * @param Request                    $request
     * @param LegislativeInitiative|null $item
     *
     * @return View|RedirectResponse
     */
    public function edit(Request $request, LegislativeInitiative $item = null)
    {
        $item = $this->getRecord($item);

        if (($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', LegislativeInitiative::class)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = LegislativeInitiative::translationFieldsProperties();
        $regulatoryActs = RegulatoryAct::all();

        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields', 'regulatoryActs'));
    }

    /**
     * Create or store updated values.
     *
     * @param StoreLegislativeInitiativeRequest $request
     * @param LegislativeInitiative|null        $item
     *
     * @return RedirectResponse
     */
    public function store(StoreLegislativeInitiativeRequest $request, LegislativeInitiative $item = null)
    {
        $item = $this->getRecord($item);
        $validated = $request->validated();
        $was_restored = false;
        $was_deleted = false;

        if (
            ($item->id && $request->user()->cannot('update', $item)) ||
            $request->user()->cannot('create', LegislativeInitiative::class)
        ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);

            if ($item->id && $request->input('deleted')) {
                $item->delete();
                $was_deleted = true;
            }

            if (!$request->input('deleted') && $item->id && $item->deleted_at) {
                $item->restore();
                $was_restored = true;
            }

            $item->save();

            $this->storeTranslateOrNewCurrent(LegislativeInitiative::TRANSLATABLE_FIELDS, $item, $validated);

            DB::commit();

            if ($was_deleted) {
                return redirect(route(self::LIST_VIEW))
                    ->with('success', trans_choice('custom.public_consultations', 1) . " " . __('messages.deleted_successfully_f'));
            }

            if ($was_restored) {
                return redirect(route(self::LIST_VIEW))
                    ->with('success', trans_choice('custom.public_consultations', 1) . " " . __('messages.restored_successfully_f'));
            }

            if ($item->id) {
                return redirect(route(self::EDIT_ROUTE, $item))
                    ->with('success', trans_choice('custom.public_consultations', 1) . " " . __('messages.updated_successfully_m'));
            }

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.public_consultations', 1) . " " . __('messages.created_successfully_m'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
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
     * @param LegislativeInitiative|null $record
     * @param array                      $with
     *
     * @return mixed
     */
    private function getRecord(LegislativeInitiative|null $record, array $with = []): mixed
    {
        $query = LegislativeInitiative::withTrashed();

        if (sizeof($with)) {
            $query->with($with);
        }

        if (!$record) {
            return new LegislativeInitiative();
        }

        $item = $query->find($record->id);
        if (!$item) {
            return new LegislativeInitiative();
        }

        return $item;
    }
}
