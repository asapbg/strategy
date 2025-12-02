<?php

namespace App\Http\Controllers\Admin\Nomenclature;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StoreAuthorityAcceptingStrategicRequest;
use App\Models\AuthorityAcceptingStrategic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthorityAcceptingStrategicController extends AdminController
{
    const LIST_ROUTE = 'admin.nomenclature.authority_accepting_strategic';
    const EDIT_ROUTE = 'admin.nomenclature.authority_accepting_strategic.edit';
    const STORE_ROUTE = 'admin.nomenclature.authority_accepting_strategic.store';
    const LIST_VIEW = 'admin.nomenclatures.authority_accepting_strategic.index';
    const EDIT_VIEW = 'admin.nomenclatures.authority_accepting_strategic.edit';

    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $active = $request->get('active') ?? 1;
        $paginate = $filter['paginate'] ?? AuthorityAcceptingStrategic::PAGINATE;

        $items = AuthorityAcceptingStrategic::with(['translation'])
            ->FilterBy($requestFilter)
            ->whereActive($active)
            ->paginate($paginate);
        $toggleBooleanModel = 'AuthorityAcceptingStrategic';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName'));
    }

    /**
     * @param Request $request
     * @param AuthorityAcceptingStrategic $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, AuthorityAcceptingStrategic $item)
    {
        if( ($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', AuthorityAcceptingStrategic::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = AuthorityAcceptingStrategic::translationFieldsProperties();
        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields'));
    }

    public function store(StoreAuthorityAcceptingStrategicRequest $request, AuthorityAcceptingStrategic $item)
    {
        $id = $item->id;
        $validated = $request->validated();
        if( ($id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', AuthorityAcceptingStrategic::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(AuthorityAcceptingStrategic::TRANSLATABLE_FIELDS, $item, $validated);

            if( $id ) {
                return redirect(route(self::EDIT_ROUTE, $item) )
                    ->with('success', trans_choice('custom.nomenclature.authority_accepting_strategic', 1)." ".__('messages.updated_successfully_m'));
            }

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.nomenclature.authority_accepting_strategic', 1)." ".__('messages.created_successfully_m'));
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
        $qItem = AuthorityAcceptingStrategic::query();
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
