<?php

namespace App\Http\Controllers\Admin\Nomenclature;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Requests\TagStoreRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TagController extends AdminController
{
    const LIST_ROUTE = 'admin.nomenclature.tag';
    const EDIT_ROUTE = 'admin.nomenclature.tag.edit';
    const STORE_ROUTE = 'admin.nomenclature.tag.store';
    const LIST_VIEW = 'admin.nomenclatures.tag.index';
    const EDIT_VIEW = 'admin.nomenclatures.tag.edit';

    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $active = $request->get('active') ?? 1;
        $filter['status']['value'] = $active;
        $requestFilter['status'] = $active;

        $paginate = $filter['paginate'] ?? Tag::PAGINATE;

        $items = Tag::with(['translation'])
            ->FilterBy($requestFilter)
            ->paginate($paginate);
        $toggleBooleanModel = 'LinkCategory';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName'));
    }

    /**
     * @param Request $request
     * @param Tag $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, Tag $item)
    {
        if (($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', LinkCategory::class)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = Tag::translationFieldsProperties();
        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields'));
    }

    public function store(TagStoreRequest $request, Tag $item)
    {
        $id = $item->id;
        $validated = $request->validated();
        if (($id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', Tag::class)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(Tag::TRANSLATABLE_FIELDS, $item, $validated);

            if ($id) {
                return redirect(route(self::EDIT_ROUTE, $item))
                    ->with('success', trans_choice('custom.nomenclature.tags', 1) . " " . __('messages.updated_successfully_m'));
            }

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.nomenclature.tags', 1) . " " . __('messages.created_successfully_m'));
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
            'status' => array(
                'type' => 'select',
                'options' => optionsStatusesFilter(true, '', __('custom.status') . ' (' . __('custom.any') . ')'),
                'default' => '',
                'placeholder' => __('validation.attributes.status'),
                'value' => $request->input('status'),
                'col' => 'col-md-2'
            )
        );
    }

    /**
     * @param $id
     * @param array $with
     */
    private function getRecord($id, array $with = []): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $qItem = Tag::query();
        if (sizeof($with)) {
            $qItem->with($with);
        }
        $item = $qItem->find((int)$id);
        if (!$item) {
            abort(Response::HTTP_NOT_FOUND);
        }
        return $item;
    }
}
