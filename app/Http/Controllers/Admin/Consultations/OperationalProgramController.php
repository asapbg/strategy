<?php

namespace App\Http\Controllers\Admin\Consultations;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StoreOperationalProgramRequest;
use App\Models\Consultations\OperationalProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OperationalProgramController extends AdminController
{
    const LIST_ROUTE = 'admin.consultations.operational_programs.index';
    const EDIT_ROUTE = 'admin.consultations.operational_programs.edit';
    const STORE_ROUTE = 'admin.consultations.operational_programs.store';
    const LIST_VIEW = 'admin.consultations.operational_programs.index';
    const EDIT_VIEW = 'admin.consultations.operational_programs.edit';

    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? OperationalProgram::PAGINATE;

        $items = OperationalProgram::with(['translation'])
            ->FilterBy($requestFilter)
            ->paginate($paginate);
        $toggleBooleanModel = 'OperationalProgram';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName'));
    }

    /**
     * @param Request $request
     * @param OperationalProgram $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, OperationalProgram $item)
    {
        if( ($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', OperationalProgram::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = OperationalProgram::translationFieldsProperties();
        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields'));
    }

    public function store(StoreOperationalProgramRequest $request, $item = null)
    {
        $item = $this->getRecord($item);
        $isEdit = (bool)$item->id;
        $validated = $request->validated();
        if( ($item->id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', OperationalProgram::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->active = $request->input('active') ? 1 : 0;
            $item->save();
            $this->storeTranslateOrNew(OperationalProgram::TRANSLATABLE_FIELDS, $item, $validated);

            if ($isEdit) {
                return redirect(route(self::EDIT_ROUTE, $item) )
                    ->with('success', trans_choice('custom.operational_program', 1)." ".__('messages.updated_successfully_m'));
            }

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.operational_program', 1)." ".__('messages.created_successfully_m'));
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
                'placeholder' => __('validation.attributes.title'),
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
        $qItem = OperationalProgram::query();
        if( sizeof($with) ) {
            $qItem->with($with);
        }
        $item = $qItem->find((int)$id);
        if( !$item ) {
            return new OperationalProgram();
        }
        return $item;
    }
}
