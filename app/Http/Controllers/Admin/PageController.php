<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PageModulesEnum;
use App\Http\Requests\PageStoreRequest;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PageController  extends AdminController
{
    const LIST_ROUTE = 'admin.page';
    const EDIT_ROUTE = 'admin.page.edit';
    const STORE_ROUTE = 'admin.page.store';
    const LIST_VIEW = 'admin.page.index';
    const EDIT_VIEW = 'admin.page.edit';

    public function index(Request $request, $module = 0)
    {
        $customEditRouteName = $customListRouteName = null;

        $requestFilter = $request->all();
        $filter = $this->filters($request);
        //request comes from some module
        if($module) {
            $requestFilter['module'] = $module;
            unset($filter['module']);
            $customEditRouteName = match ((int)$module) {
                PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value => 'admin.impact_assessments.library.edit',
                default => null,
            };
            $customListRouteName = match ((int)$module) {
                PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value => 'admin.impact_assessments.library',
                default => null,
            };
        }

        $paginate = $filter['paginate'] ?? Page::PAGINATE;

        if( !isset($requestFilter['active']) ) {
            $requestFilter['active'] = 1;
        }
        $items = Page::select('page.*')->with(['translations'])
            ->leftJoin('page_translations', function ($j){
                $j->on('page_translations.page_id', '=', 'page.id')
                    ->where('page_translations.locale', '=', app()->getLocale());
            })
            ->FilterBy($requestFilter)
            ->orderBy('page_translations.name', 'asc')
            ->paginate($paginate);
        $toggleBooleanModel = 'Page';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel',
            'editRouteName', 'listRouteName', 'customEditRouteName', 'customListRouteName', 'module'));
    }

    /**
     * @param Request $request
     * @param int $item
     * @param int $module
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, int $item, int $module = 0)
    {
        $customStoreRouteName = $customListRouteName = null;
        //request comes from some module
        if($module) {
            $customStoreRouteName = match ($module) {
                PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value => 'admin.impact_assessments.page.store',
                default => null,
            };
            $customListRouteName = match ($module) {
                PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value => 'admin.impact_assessments.library',
                default => null,
            };
        }

        if (!$item) {
            $item = new Page();
        } else {
            $item = Page::find($item);
        }
        if( ($item && isset($item->id) && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', Page::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = Page::translationFieldsProperties();
        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields',
            'customStoreRouteName', 'customListRouteName', 'module'));
    }

    public function store(PageStoreRequest $request, int $module = 0)
    {
        //TODO delete cache pages
        $validated = $request->validated();
        $id = $validated['id'];
        $item = $id ? Page::find($id) : new Page();

        if( ($id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', Page::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            if( empty($validated['slug']) ) {
                $validated['slug'] = Str::slug($validated['name_bg']);
            }

            if($item->in_footer != (int)isset($validated['in_footer'])){
                Cache::forget(Page::CACHE_FOOTER_PAGES_KEY);
            }

            $fillable = $this->getFillableValidated($validated, $item);
            $item->in_footer = (int)isset($validated['in_footer']);
            if($module && !$id){
                $fillable['module_enum'] = $module;
            }
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(Page::TRANSLATABLE_FIELDS, $item, $validated);

            DB::commit();
            if( $id ) {
                $route = route(self::EDIT_ROUTE, $item);
                //request comes from some module
                if($module) {
                    $route = match ((int)$module) {
                        PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value => route('admin.impact_assessments.library.edit', ['item' => $item, 'module' => $module]),
                        default => null,
                    };
                }
                return redirect($route)
                    ->with('success', trans_choice('custom.pages', 1)." ".__('messages.updated_successfully_m'));
            }

            $route = route(self::EDIT_ROUTE, $item);
            //request comes from some module
            if($module) {
                $route = match ((int)$module) {
                    PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value => route('admin.impact_assessments.library', ['module' => $module]),
                    default => null,
                };
            }
            return redirect($route)
                ->with('success', trans_choice('custom.pages', 1)." ".__('messages.created_successfully_m'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
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
            ),
            'module' => array(
                'type' => 'select',
                'placeholder' => trans_choice('custom.modules', 1),
                'options' => enumToSelectOptions(PageModulesEnum::options(), 'page.module', true),
                'value' => $request->input('module'),
                'col' => 'col-md-4'
            ),
            'inFooter' => array(
                'type' => 'checkbox',
                'checked' => $request->input('inFooter'),
                'placeholder' => __('custom.in_footer'),
                'value' => 1,
                'col' => 'col-md-4',
                'class' => 'fw-normal'
            )
        );
    }

    /**
     * @param $id
     * @param array $with
     */
    private function getRecord($id, array $with = []): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $qItem = Page::query();
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
