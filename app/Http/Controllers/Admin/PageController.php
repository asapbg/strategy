<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PageModulesEnum;
use App\Http\Requests\PageOrderFilesRequest;
use App\Http\Requests\PageStoreRequest;
use App\Models\CustomRole;
use App\Models\File;
use App\Models\Page;
use App\Models\Publication;
use Illuminate\Http\RedirectResponse;
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
        $customEditRouteName = null;

        $requestFilter = $request->all();
        $filter = $this->filters($request);
        //request comes from some module
        $customListRouteName = 'admin.page';
        $customDeleteRouteName = 'admin.page.delete';
        if($module) {
            if(($module == PageModulesEnum::MODULE_OGP->value && !$request->user()->canAny(['manage.*', 'manage.partnership']))
            || ($module == PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value && !$request->user()->canAny(['manage.*']))){
                return back()->with('warning', __('messages.unauthorized'));
            }

            $requestFilter['module'] = $module;
            unset($filter['module']);
            $customEditRouteName = match ((int)$module) {
                PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value => 'admin.impact_assessments.library.edit',
                PageModulesEnum::MODULE_OGP->value => 'admin.ogp.library.edit',
                default => null,
            };
            $customListRouteName = match ((int)$module) {
                PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value => 'admin.impact_assessments.library',
                PageModulesEnum::MODULE_OGP->value => 'admin.ogp.library',
                default => null,
            };
            $customDeleteRouteName = match ((int)$module) {
                PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value => 'admin.impact_assessments.page.delete',
                PageModulesEnum::MODULE_OGP->value => 'admin.ogp.page.delete',
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
            'editRouteName', 'listRouteName', 'customEditRouteName', 'customListRouteName', 'module', 'customDeleteRouteName'));
    }

    /**
     * @param Request $request
     * @param int $item
     * @param int $module
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, int $item, int $module = 0)
    {
        $customStoreRouteName = $customListRouteName = $customOrderFilesRoute = null;

        if(($module == PageModulesEnum::MODULE_OGP->value && !$request->user()->canAny(['manage.*', 'manage.partnership']))
            || ($module == PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value && !$request->user()->canAny(['manage.*']))){
            return back()->with('warning', __('messages.unauthorized'));
        }

        //request comes from some module
        if($module) {
            $customStoreRouteName = match ($module) {
                PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value => 'admin.impact_assessments.page.store',
                PageModulesEnum::MODULE_OGP->value => 'admin.ogp.page.store',
                default => null,
            };
            $customListRouteName = match ($module) {
                PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value => 'admin.impact_assessments.library',
                PageModulesEnum::MODULE_OGP->value => 'admin.ogp.library',
                default => null,
            };
            $customOrderFilesRoute = match ($module) {
                PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value => 'admin.impact_assessments.library.edit.order_files',
                PageModulesEnum::MODULE_OGP->value => 'admin.ogp.library.edit.order_files',
                default => null,
            };
        }

        if (!$item) {
            $item = new Page();
        } else {
            $item = Page::find($item);
        }
        if( ($item && isset($item->id) && $request->user()->cannot('update', $item)) || (!$item && $request->user()->cannot('create', Page::class)) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = Page::translationFieldsProperties();
        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields',
            'customStoreRouteName', 'customListRouteName', 'customOrderFilesRoute', 'module'));
    }

    /**
     * @param Request $request
     * @param int $item
     * @param int $module
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function orderFiles(PageOrderFilesRequest $request, int $item, int $module = 0)
    {
        $validated = $request->validated();
        if(
            (!$module && $request->user()->cannot('update', $item))
            || (
                $module
                && (
                    ($module == PageModulesEnum::MODULE_OGP->value && $request->user()->cannot('update', $item))
                    || ($module == PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value && $request->user()->cannot('update', $item))
                )
            )){

            DB::beginTransaction();
            try {
                foreach ($validated['file_id'] as $key => $fid){
                    File::where('id', '=', $fid)->update(['ord' => (int)$validated['ord'][$key]]);
                }
                DB::commit();
                $route = route(self::EDIT_ROUTE, $item);
                if($module){
                    $route = match ((int)$module) {
                        PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value => route('admin.impact_assessments.library.edit', ['item' => $item, 'module' => $module]),
                        PageModulesEnum::MODULE_OGP->value => route('admin.ogp.library.edit', ['item' => $item, 'module' => $module]),
                        default => null,
                    };
                }

                return redirect($route.'#ct-files')
                    ->with('success', 'Промяната в поредността на файловете е записана');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error($e);
                return redirect(url()->previous())->with('danger', __('messages.system_error'));

            }
            return back()->with('warning', __('messages.unauthorized'));
        }
    }

    public function store(PageStoreRequest $request, int $module = 0)
    {
        $validated = $request->validated();
        $id = $validated['id'];
        $item = $id ? Page::find($id) : new Page();

        if(!$id && (!isset($validated['slug']) || empty($validated['slug']))){
            $existSlug = Page::where('slug', '=', Str::slug($validated['name_bg']))->first();
            if($existSlug){
                return back()->withInput()->with('danger', 'Вече съществува страница с това име');
            }
        }

        if(
            (!$module && ( ($id && $request->user()->cannot('update', $item)) || (!$id && $request->user()->cannot('create', Page::class)) ))
            || (
                $module
                && (
                    ($module == PageModulesEnum::MODULE_OGP->value && (($id && $request->user()->cannot('update', $item)) || (!$request->user()->canAny(['manage.*', 'manage.partnership']))))
                    || ($module == PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value && (($id && $request->user()->cannot('update', $item)) || (!$request->user()->canAny(['manage.*']))))
                )
            )){
            return back()->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            if( empty($validated['slug']) ) {
                $validated['slug'] = Str::slug($validated['name_bg']);
            }

//            if($item->in_footer != (int)isset($validated['in_footer'])){
//                Cache::forget(Page::CACHE_FOOTER_PAGES_KEY);
//            }

            $fillable = $this->getFillableValidated($validated, $item);
            $item->in_footer = (int)isset($validated['in_footer']);
            if($module && !$id){
                $fillable['module_enum'] = $module;
            }
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(Page::TRANSLATABLE_FIELDS, $item, $validated);

            DB::commit();

            Cache::forget(Page::CACHE_FOOTER_PAGES_KEY . '_' . app()->getLocale());
            Cache::forget(Page::CACHE_FOOTER_TERMS_PAGES . '_' . app()->getLocale());

            //request comes from some module
            if($module) {
                $modulePagesCacheKey = match ($module){
                    PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value => Page::CACHE_MODULE_PAGES_IMPACT_ASSESSMENT,
                    PageModulesEnum::MODULE_OGP->value => Page::CACHE_MODULE_PAGES_OGP,
                    default => null,
                };

                if($modulePagesCacheKey){
                    Cache::forget($modulePagesCacheKey);
                }
            }

            $route = route(self::EDIT_ROUTE, $item);
            if($module){
                $route = match ((int)$module) {
                    PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value => route('admin.impact_assessments.library.edit', ['item' => $item, 'module' => $module]),
                    PageModulesEnum::MODULE_OGP->value => route('admin.ogp.library.edit', ['item' => $item, 'module' => $module]),
                    default => null,
                };
            }
            if( $id ) {
                return redirect($route)
                    ->with('success', trans_choice('custom.pages', 1)." ".__('messages.updated_successfully_m'));
            }

            return redirect($route)
                ->with('success', trans_choice('custom.pages', 1)." ".__('messages.created_successfully_m'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }

    }

    /**
     * Delete existing publication
     *
     * @param Request $request
     * @param Page $item
     * @param int $module
     * @return RedirectResponse
     */
    public function destroy(Request $request, Page $item, int $module = 0)
    {
        if($request->user()->cannot('delete', $item)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        try {
            $item->delete();
            $route = route(self::LIST_ROUTE);
            if($module){
                $route = match ($module) {
                    PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value => route('admin.impact_assessments.library', ['module' => $module]),
                    PageModulesEnum::MODULE_OGP->value => route('admin.ogp.library', ['module' => $module]),
                    default => null,
                };
            }
            return redirect($route)
                ->with('success', __('custom.the_record')." ".__('messages.deleted_successfully_m'));
        }
        catch (\Exception $e) {
            Log::error($e);
            return redirect(url()->previous())->with('danger', __('messages.system_error'));

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
