<?php

namespace App\Http\Controllers\Admin\StrategicDocuments;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StoreInstitutionRequest;
use App\Models\StrategicDocuments\Institution;
use App\Models\ConsultationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class InstitutionController extends AdminController
{
    const LIST_ROUTE = 'admin.strategic_documents.institutions.index';
    const EDIT_ROUTE = 'admin.strategic_documents.institutions.edit';
    const STORE_ROUTE = 'admin.strategic_documents.institutions.store';
    const LIST_VIEW = 'admin.strategic_documents.institutions.index';
    const EDIT_VIEW = 'admin.strategic_documents.institutions.edit';

    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? Institution::PAGINATE;

        $items = Institution::with(['translation'])
            ->FilterBy($requestFilter)
            ->paginate($paginate);
        $toggleBooleanModel = 'Institution';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName'));
    }

    /**
     * @param Request $request
     * @param Institution $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, Institution $item)
    {
        if( ($item->id && $request->user()->cannot('update', $item)) || (!$item->id && $request->user()->cannot('create', Institution::class)) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = Institution::translationFieldsProperties();
        $consultationLevels = ConsultationLevel::all();
        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields', 'consultationLevels'));
    }

    public function store(StoreInstitutionRequest $request, $item = null)
    {
        $item = $this->getRecord($item);
        $validated = $request->validated();
        if( ($item->id && $request->user()->cannot('update', $item))
            || (!$item->id && $request->user()->cannot('create', Institution::class)) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(Institution::TRANSLATABLE_FIELDS, $item, $validated);

            if ($item->id) {
                return redirect(route(self::EDIT_ROUTE, $item) )
                    ->with('success', trans_choice('custom.institution', 1)." ".__('messages.updated_successfully_m'));
            }

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.institution', 1)." ".__('messages.created_successfully_m'));
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
        $qItem = Institution::query();
        if( sizeof($with) ) {
            $qItem->with($with);
        }
        $item = $qItem->find((int)$id);
        if( !$item ) {
            return new Institution();
        }
        return $item;
    }
}
