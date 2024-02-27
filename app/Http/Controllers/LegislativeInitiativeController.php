<?php

namespace App\Http\Controllers;

use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\CloseLegislativeInitiativeRequest;
use App\Http\Requests\StoreLegislativeInitiativeRequest;
use App\Http\Requests\UpdateLegislativeInitiativeRequest;
use App\Models\Consultations\OperationalProgramRow;
use App\Models\LegislativeInitiative;
use App\Models\RegulatoryAct;
use App\Models\Setting;
use App\Models\StrategicDocuments\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class LegislativeInitiativeController extends AdminController
{

    const LIST_ROUTE = 'legislative_initiatives.index';
    const EDIT_ROUTE = 'legislative_initiatives.edit';
    const STORE_ROUTE = 'legislative_initiatives.store';
    const LIST_VIEW = 'site.legislative_initiatives.index';
    const EDIT_VIEW = 'site.legislative_initiatives.edit';
    const CREATE_VIEW = 'site.legislative_initiatives.create';
    const SHOW_VIEW = 'site.legislative_initiatives.view';

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->title_singular = __('custom.legislative_initiatives');
        $this->pageTitle = __('custom.legislative_initiatives');
    }

    /**
     * Show the public consultations.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $institutions = Institution::select('id')->orderBy('id')->with('translation')->get();
        $countResults = $request->get('count_results', 10);
        $keywords = $request->offsetGet('keywords');
        $institution = $request->offsetGet('institution');
        $order_by = $request->offsetGet('order_by');
        $order_by_direction = $request->offsetGet('direction');

        $items = LegislativeInitiative::with(['comments'])
            ->when(!empty($keywords), function ($query) use ($keywords) {
                    $query->whereHas('operationalProgramTitle', function ($query) use ($keywords) {
                        $query->where('value', 'ilike', "%$keywords%");
                    })
                    ->orWhere('description', 'like', '%' . $keywords . '%')
                    ->orWhereHas('user', function ($query) use ($keywords) {
                        $query->where('first_name', 'like', '%' . $keywords . '%');
                        $query->orWhere('middle_name', 'like', '%' . $keywords . '%');
                        $query->orWhere('last_name', 'like', '%' . $keywords . '%');
                    });
            })
            ->when(!empty($institution), function ($query) use ($institution) {
                $query->whereHas('operationalProgram', function ($query) use ($institution) {
                    $query->where('dynamic_structures_column_id', '=', config('lp_op_programs.op_ds_col_institution_id'))
                    ->whereHas('institutions', function ($query) use ($institution) {
                        $query->where('institution.id', '=', $institution);
                    });
                });
            })
            ->when(!empty($order_by), function ($query) use ($order_by, $order_by_direction) {
                $direction = !in_array($order_by_direction, ['asc', 'desc']) ? 'asc' : $order_by_direction;

                $query = match ($order_by) {
                    'keywords' => $query->orderBy('description', $direction),
                    'institutions' => $query->orderBy('operational_program_id', $direction),
                    default => $query->orderBy('created_at', $direction),
                };
            })
            ->when(empty($order_by), function ($query) {
                $query = $query->orderBy('status');
            })
            ->paginate($countResults);

        $pageTitle = $this->pageTitle;
        $this->composeBreadcrumbs();
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_LI.'_'.app()->getLocale())->first();
        return $this->view(self::LIST_VIEW, compact('items', 'institutions','pageTitle', 'pageTopContent'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $regulatoryActs = RegulatoryAct::orderBy('id')->get();
        $translatableFields = LegislativeInitiative::translationFieldsProperties();
        $item = new LegislativeInitiative();
        $pageTitle = $this->pageTitle;
        return $this->view(self::CREATE_VIEW, compact('regulatoryActs', 'translatableFields', 'item', 'pageTitle'));
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

        $pageTitle = $this->pageTitle;
        $this->composeBreadcrumbs($item);
        return $this->view(self::EDIT_VIEW, compact('item', 'pageTitle', 'storeRouteName', 'listRouteName', 'translatableFields', 'regulatoryActs'));
    }

    public function store(StoreLegislativeInitiativeRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $validated['author_id'] = auth()->user()->id;

            $new = new LegislativeInitiative();
            $new->fill($validated);
            $new->save();

            DB::commit();

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.legislative_initiatives_list', 1) . " " . __('messages.created_successfully_f'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function show(LegislativeInitiative $item)
    {
        $pageTitle = $this->pageTitle;
        $this->composeBreadcrumbs($item);
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_LI.'_'.app()->getLocale())->first();
        return $this->view(self::SHOW_VIEW, compact('item', 'pageTopContent', 'pageTitle'));
    }

    public function update(UpdateLegislativeInitiativeRequest $request, LegislativeInitiative $item)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $item->fill($validated);
            $item->save();

            DB::commit();

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.legislative_initiatives_list', 1) . " " . __('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function destroy(CloseLegislativeInitiativeRequest $request, LegislativeInitiative $item)
    {
        try {
            $item->setStatus(LegislativeInitiativeStatusesEnum::STATUS_CLOSED);
            $item->save();

            return redirect(route(self::LIST_ROUTE, $item))
                ->with('success', trans_choice('custom.legislative_initiatives', 1) . " " . __('messages.deleted_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect(route(self::LIST_ROUTE, $item))->with('danger', __('messages.system_error'));
        }
    }

    /**
     * @param $item
     * @param $extraItems
     * @return void
     */
    private function composeBreadcrumbs($item = null, $extraItems = []){
        $customBreadcrumbs = array(
            ['name' => __('custom.legislative_initiatives'), 'url' => route('legislative_initiatives.index')]
        );

        if($item){
            $customBreadcrumbs[] = [
                'name' => (__('custom.change_f').' '.__('custom.in').' '.$item->operationalProgram?->value),
                'url' => (!empty($extraItems) ? route('legislative_initiatives.view', $item) : null)
            ];
        }
        if(!empty($extraItems)){
            foreach ($extraItems as $eItem){
                $customBreadcrumbs[] = $eItem;
            }
        }
        $this->setBreadcrumbsFull($customBreadcrumbs);
    }
}
