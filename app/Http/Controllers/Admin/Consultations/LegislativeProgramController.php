<?php

namespace App\Http\Controllers\Admin\Consultations;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StoreLegislativeProgramRequest;
use App\Models\Consultations\LegislativeProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LegislativeProgramController extends AdminController
{
    const LIST_ROUTE = 'admin.consultations.legislative_programs.index';
    const EDIT_ROUTE = 'admin.consultations.legislative_programs.edit';
    const STORE_ROUTE = 'admin.consultations.legislative_programs.store';
    const LIST_VIEW = 'admin.consultations.legislative_programs.index';
    const EDIT_VIEW = 'admin.consultations.legislative_programs.edit';

    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? LegislativeProgram::PAGINATE;

        $items = LegislativeProgram::with(['translation'])
            ->FilterBy($requestFilter)
            ->paginate($paginate);
        $toggleBooleanModel = 'LegislativeProgram';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName'));
    }

    /**
     * @param Request $request
     * @param LegislativeProgram $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, LegislativeProgram $item)
    {
        if( ($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', LegislativeProgram::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = LegislativeProgram::translationFieldsProperties();
        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields'));
    }

    public function store(StoreLegislativeProgramRequest $request, $item = null)
    {
        $item = $this->getRecord($item);
        $isEdit = (bool)$item->id;
        $validated = $request->validated();
        if( ($item->id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', LegislativeProgram::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->active = $request->input('active') ? 1 : 0;
            $item->save();
            $this->storeTranslateOrNew(LegislativeProgram::TRANSLATABLE_FIELDS, $item, $validated);

            if ($isEdit) {
                return redirect(route(self::EDIT_ROUTE, $item) )
                    ->with('success', trans_choice('custom.legislative_program', 1)." ".__('messages.updated_successfully_m'));
            }

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.legislative_program', 1)." ".__('messages.created_successfully_m'));
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
                'placeholder' => __('validation.attributes.name'),
                'value' => $request->input('title'),
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
        $qItem = LegislativeProgram::query();
        if( sizeof($with) ) {
            $qItem->with($with);
        }
        $item = $qItem->find((int)$id);
        if( !$item ) {
            return new LegislativeProgram();
        }
        return $item;
    }
}
