<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StorePageRequest;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends AdminController
{
    const LIST_ROUTE = 'admin.pages.index';
    const EDIT_ROUTE = 'admin.pages.edit';
    const STORE_ROUTE = 'admin.pages.store';
    const LIST_VIEW = 'admin.pages.index';
    const EDIT_VIEW = 'admin.pages.edit';
    const PAGE_TYPE = Page::TYPE_STATIC_CONTENT;
    const MODEL_NAME = 'custom.static_content';

    /**
     * Show the public consultations.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? Page::PAGINATE;
        $pageType = static::PAGE_TYPE;

        $items = Page::with(['translation'])
            ->whereType($pageType)
            ->FilterBy($requestFilter)
            ->paginate($paginate);
        $toggleBooleanModel = 'Page';
        $editRouteName = static::EDIT_ROUTE;
        $listRouteName = static::LIST_ROUTE;
        $modelName = static::MODEL_NAME;
        return $this->view(static::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName', 'pageType', 'modelName'));
    }

    /**
     * @param Request $request
     * @param Page $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $item = null)
    {
        $item = $this->getRecord($item);
        if( ($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', Page::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = static::STORE_ROUTE;
        $listRouteName = static::LIST_ROUTE;
        $translatableFields = Page::translationFieldsProperties();
        $pageType = static::PAGE_TYPE;
        $modelName = static::MODEL_NAME;
        
        return $this->view(static::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields', 'pageType', 'modelName'));
    }

    public function store(StorePageRequest $request, $item = null)
    {
        $item = $this->getRecord($item);
        $validated = $request->validated();
        if( ($item->id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', Page::class) ) {
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
            $this->storeTranslateOrNewCurrent(Page::TRANSLATABLE_FIELDS, $item, $validated);

            if( $item->id ) {
                return redirect(route(static::EDIT_ROUTE, $item) )
                    ->with('success', trans_choice('custom.pages', 1)." ".__('messages.updated_successfully_m'));
            }

            return to_route(static::LIST_ROUTE)
                ->with('success', trans_choice('custom.pages', 1)." ".__('messages.created_successfully_m'));
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
        );
    }

    /**
     * @param $id
     * @param array $with
     */
    private function getRecord($id, array $with = []): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $qItem = Page::withTrashed();
        if( sizeof($with) ) {
            $qItem->with($with);
        }
        $item = $qItem->find((int)$id);
        if( !$item ) {
            return new Page();
        }
        return $item;
    }

    public static function getCategories()
    {
        return PageCategory::all();
    }
}
