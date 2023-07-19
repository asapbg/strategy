<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StorePublicationRequest;
use App\Models\Publication;
use App\Models\PublicationCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicationController extends AdminController
{
    const LIST_ROUTE = 'admin.publications.index';
    const EDIT_ROUTE = 'admin.publications.edit';
    const STORE_ROUTE = 'admin.publications.store';
    const LIST_VIEW = 'admin.publications.index';
    const EDIT_VIEW = 'admin.publications.edit';
    const PUBLICATION_TYPE = Publication::TYPE_PUBLICATION;

    /**
     * Show the public consultations.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? Publication::PAGINATE;
        $publicationType = static::PUBLICATION_TYPE;

        $items = Publication::with(['translation'])
            ->whereType($publicationType)
            ->FilterBy($requestFilter)
            ->paginate($paginate);
        $toggleBooleanModel = 'Publication';
        $editRouteName = static::EDIT_ROUTE;
        $listRouteName = static::LIST_ROUTE;
        return $this->view(static::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName', 'publicationType'));
    }

    /**
     * @param Request $request
     * @param Publication $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $item = null)
    {
        $item = $this->getRecord($item);
        if( ($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', Publication::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = static::STORE_ROUTE;
        $listRouteName = static::LIST_ROUTE;
        $translatableFields = Publication::translationFieldsProperties();
        $publicationType = static::PUBLICATION_TYPE;
        
        $publicationCategories = PublicationCategory::all();
        return $this->view(static::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields', 'publicationCategories', 'publicationType'));
    }

    public function store(StorePublicationRequest $request, $item = null)
    {
        $item = $this->getRecord($item);
        $validated = $request->validated();
        if( ($item->id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', Publication::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->active = $request->input('active') ? 1 : 0;
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
            $this->storeTranslateOrNewCurrent(Publication::TRANSLATABLE_FIELDS, $item, $validated);

            if( $item->id ) {
                return redirect(route(static::EDIT_ROUTE, $item) )
                    ->with('success', trans_choice('custom.publications', 1)." ".__('messages.updated_successfully_m'));
            }

            return to_route(static::LIST_ROUTE)
                ->with('success', trans_choice('custom.publications', 1)." ".__('messages.created_successfully_m'));
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
                'col' => 'col-md-3'
            ),
            'category' => array(
                'type' => 'select',
                'value' => $request->input('category'),
                'options' => PublicationCategory::all()->map(function($item) {
                    return ['value' => $item->id, 'name' => $item->name];
                })->prepend(['value' => null, 'name' => __('validation.attributes.category')]),
                'col' => 'col-md-2'
            ),
            'from' => array(
                'type' => 'datepicker',
                'placeholder' => __('validation.attributes.date_from'),
                'value' => $request->input('from'),
                'col' => 'col-md-2'
            ),
            'to' => array(
                'type' => 'datepicker',
                'placeholder' => __('validation.attributes.date_to'),
                'value' => $request->input('to'),
                'col' => 'col-md-2'
            ),
        );
    }

    /**
     * @param $id
     * @param array $with
     */
    private function getRecord($id, array $with = []): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $qItem = Publication::withTrashed();
        if( sizeof($with) ) {
            $qItem->with($with);
        }
        $item = $qItem->find((int)$id);
        if( !$item ) {
            return new Publication();
        }
        return $item;
    }
}
