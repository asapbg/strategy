<?php

namespace App\Http\Controllers;

use App\Models\LegalActType;
use App\Models\Pris;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PrisController extends Controller
{
    public function index(Request $request, $category = '')
    {
        $rssUrl = config('feed.feeds.pris.url');

        $can_access_orders = $this->canAccessOrders($request);

        //Filter
        $rf = $request->all();
        $requestFilter = $request->all();
        if (isset($requestFilter['legalАctТype']) && !empty($requestFilter['legalАctТype']) && !empty($category)) {
            $actType = LegalActType::with(['translations'])->find((int)$requestFilter['legalАctТype']);
            if ($actType && Str::slug($actType->name) != $category) {
                return redirect(route('pris.index', $request->query()));
            }
        }

        $filter = $this->filters($request, $can_access_orders);
        $filter['fullSearch']['label'] .= "/" . __('custom.search_in_archive');
        $filter['formGroup']['fields']['in_archive'] = [
            'type' => 'checkbox',
            'checked' => $request->ajax() ? $request->input('in_archive') : true,
            'label' => __('custom.search_in_archive'),
            'value' => 1,
            'col' => 'col-md-1 d-inline me-2'
        ];

        //Sorter
        $sorter = $this->sorters();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'docDate';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');

        $paginate = $requestFilter['paginate'] ?? config('app.default_paginate');
        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        $in_archive = $request->offsetGet('in_archive');

        $items = Pris::select('pris.*')
            ->when(!$in_archive, function ($query) {
                $query->where('pris.in_archive', 0);
            })
            ->LastVersion()
            //->InPris()
            ->Published()
            ->whereActive(true)
            ->with(['translations', 'actType.translations', 'institutions.historyNames', 'institutions.translation'])
            ->leftJoin('pris_translations', function ($j) {
                $j->on('pris_translations.pris_id', '=', 'pris.id')
                    ->where('pris_translations.locale', '=', app()->getLocale());
            })
//            ->join('legal_act_type', 'legal_act_type.id', '=', 'pris.legal_act_type_id')
//            ->join('legal_act_type_translations', function ($j) {
//                $j->on('legal_act_type_translations.legal_act_type_id', '=', 'legal_act_type.id')
//                    ->where('legal_act_type_translations.locale', '=', app()->getLocale());
//            })
            ->where('pris.legal_act_type_id', '<>', LegalActType::TYPE_ARCHIVE)
            ->when(!$can_access_orders, function ($query) {
                $query->where('pris.legal_act_type_id', '<>', LegalActType::TYPE_ORDER);
            })
            ->FilterBy($requestFilter)
            ->SortedBy($sort, $sortOrd)
            //->GroupBy('pris.id', 'institution_translations.name', 'legal_act_type_translations.name')
            ->paginate($paginate);


        $hasSubscribeEmail = $this->hasSubscription(null, Pris::class, $requestFilter);
        $hasSubscribeRss = false;

        $closeSearchForm = true;
        if ($request->ajax()) {
            $closeSearchForm = false;
            return view('site.pris.list',
                compact('filter', 'sorter', 'items', 'rf', 'hasSubscribeEmail', 'hasSubscribeRss', 'requestFilter', 'rssUrl', 'closeSearchForm')
            );
        }

        [ $menuCategories, $menuCategoriesArchive ] = $this->getPrisProgramsMenuItems($can_access_orders);

        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_PRIS . '_' . app()->getLocale())->first();

        $pageTitle = __('site.pris.page_title');
        $extraBreadCrumbs = [];
        if (isset($requestFilter['legalАctТype']) && $requestFilter['legalАctТype']) {
            $actType = LegalActType::with(['translations'])->find((int)$requestFilter['legalАctТype']);
            if ($actType) {
                $extraBreadCrumbs[] = ['name' => $actType->name, 'url' => ''];
            }
        }
        $this->composeBreadcrumbs($extraBreadCrumbs);
        return $this->view('site.pris.index',
            compact(
                'filter',
                'sorter',
                'items', 'pageTitle',
                'menuCategories',
                'menuCategoriesArchive',
                'pageTopContent',
                'rf',
                'defaultOrderBy',
                'defaultDirection',
                'hasSubscribeEmail',
                'hasSubscribeRss',
                'requestFilter',
                'rssUrl',
                'closeSearchForm'
            )
        );
    }

    public function archive(Request $request, $category = '')
    {
        //Filter
        $rf = $request->all();
        $requestFilter = $request->all();
        if (isset($requestFilter['legalАctТype']) && !empty($requestFilter['legalАctТype']) && !empty($category)) {
            $actType = LegalActType::with(['translations'])->find((int)$requestFilter['legalАctТype']);
            if ($actType && Str::slug($actType->name) != $category) {
                return redirect(route('pris.index', $request->query()));
            }
        }

        $can_access_orders = $this->canAccessOrders($request);

        $filter = $this->filters($request, $can_access_orders);
        $filter['fullSearch']['label'] .= "/" . __('custom.pris_actual_acts');
        $filter['formGroup']['fields']['in_current'] = [
            'type' => 'checkbox',
            'checked' => $request->ajax() ? $request->input('in_current') : false,
            'label' => __('custom.pris_actual_acts'),
            'value' => 1,
            'col' => 'col-md-1 d-inline me-2'
        ];

        //Sorter
        $sorter = $this->sorters();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'docDate';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');

        $paginate = $requestFilter['paginate'] ?? config('app.default_paginate');
        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        $in_current = $request->offsetGet('in_current');
//        $institutions = $requestFilter['institutions'] ?? null;
//        unset($requestFilter['institutions']);

        $items = Pris::select('pris.*')
            ->when(!$in_current, function ($query) {
                $query->where('pris.in_archive', 1);
            })
            ->LastVersion()
            ->InPris()
            ->Published()
            ->with(['translations', 'actType', 'actType.translations', 'institutions.historyNames', 'institutions.translation'])
            ->leftJoin('pris_translations', function ($j) {
                $j->on('pris_translations.pris_id', '=', 'pris.id')
                    ->where('pris_translations.locale', '=', app()->getLocale());
            })
//            ->when($institutions, function ($query) use ($institutions) {
//                $query->join(
//                    'pris_institution as pi',
//                    'pi.pris_id',
//                    '=',
//                    DB::raw("pris.id AND pi.institution_id IN(".implode(',', $institutions).")")
//                )
//                    ->join('institution', 'institution.id', '=', DB::raw("pi.institution_id AND institution.active = '1' AND institution.deleted_at IS NULL"))
//                    ->join('institution_translations as it', 'it.institution_id', '=', DB::raw("pi.institution_id AND it.locale = '".app()->getLocale()."'"));
//            })
            ->join('legal_act_type', 'legal_act_type.id', '=', 'pris.legal_act_type_id')
            ->join('legal_act_type_translations', function ($j) {
                $j->on('legal_act_type_translations.legal_act_type_id', '=', 'legal_act_type.id')
                    ->where('legal_act_type_translations.locale', '=', app()->getLocale());
            })
            ->where('pris.legal_act_type_id', '<>', LegalActType::TYPE_ARCHIVE)
            ->when(!$can_access_orders, function ($query) {
                $query->where('pris.legal_act_type_id', '<>', LegalActType::TYPE_ORDER);
            })
            ->FilterBy($requestFilter)
            ->SortedBy($sort, $sortOrd)
            ->paginate($paginate);


        $hasSubscribeEmail = $this->hasSubscription(null, Pris::class, $requestFilter);
        $hasSubscribeRss = false;
        $no_rss = true;
        $no_email_subscribe = true;

        if ($request->ajax()) {
            return view('site.pris.list', compact('filter', 'sorter', 'items', 'rf', 'hasSubscribeEmail',
                'hasSubscribeRss', 'requestFilter', 'no_rss', 'no_email_subscribe'));
        }

        $menuCategories = [];
        $menuCategoriesArchive = [];
        $actTypes = LegalActType::with(['translations'])
            ->Pris()
            ->when(!$can_access_orders, function ($query) {
                $query->where('id', '<>', LegalActType::TYPE_ORDER);
            })
            ->where('id', '<>', LegalActType::TYPE_ARCHIVE)
            ->get();
        if ($actTypes->count()) {
            foreach ($actTypes as $act) {
                $menuCategories[] = [
                    'label' => $act->name,
                    'url' => route('pris.category', ['category' => Str::slug($act->name)]) . '?legalАctТype=' . $act->id,
                    'slug' => Str::slug($act->name)
                ];
                $menuCategoriesArchive[] = [
                    'label' => $act->name,
                    'url' => route('pris.archive.category', ['category' => Str::slug($act->name)]) . '?legalАctТype=' . $act->id,
                    'slug' => Str::slug($act->name)
                ];
            }
        }
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_PRIS . '_' . app()->getLocale())->first();

        $pageTitle = __('site.menu.pris');
        $extraBreadCrumbs = array(['name' => __('site.pris.archive'), 'url' => route('pris.archive')]);
        if (isset($requestFilter['legalАctТype']) && $requestFilter['legalАctТype']) {
            $actType = LegalActType::with(['translations'])->find((int)$requestFilter['legalАctТype']);
            if ($actType) {
                $extraBreadCrumbs[] = ['name' => $actType->name, 'url' => ''];
            }
        }
        $this->composeBreadcrumbs($extraBreadCrumbs);
        return $this->view('site.pris.index', compact('filter', 'sorter', 'items', 'pageTitle', 'menuCategories',
            'menuCategoriesArchive', 'pageTopContent', 'rf', 'defaultOrderBy', 'defaultDirection', 'hasSubscribeEmail', 'hasSubscribeRss',
            'requestFilter', 'no_rss', 'no_email_subscribe'));
    }

    public function show(Request $request, $category, int $id = 0)
    {
        $can_access_orders = $this->canAccessOrders($request);

        $item = Pris::LastVersion()
            //->InPris()
            ->whereActive(true)
            ->when(!$can_access_orders, function ($query) {
                $query->where('legal_act_type_id', '<>', LegalActType::TYPE_ORDER);
            })
            ->Published()
            ->with([
                'translation', 'actType', 'actType.translation', 'tags', 'tags.translation', 'changedDocsWithoutRelation',
                'changedDocs.actType.translation', 'changedDocs.institution.translation', 'files'
            ])
            ->find($id);

        if (!$item) {
            abort(Response::HTTP_NOT_FOUND);
        }
        //dd($item->changedDocsWithoutRelation);

        if ($item->legal_act_type_id == LegalActType::TYPE_TRANSCRIPTS) {
            $item->files = $item->files->sortByDesc('filename');
        }

        //dd($item->files->toArray());
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_PRIS . '_' . app()->getLocale())->first();

        $menuCategories = [];
        $menuCategoriesArchive = [];
        $actTypes = LegalActType::when(!$can_access_orders, function ($query) {
                $query->where('id', '<>', LegalActType::TYPE_ORDER);
            })
            //->Pris()
            ->where('id', '<>', LegalActType::TYPE_ARCHIVE)
            ->get();
        if ($actTypes->count()) {
            foreach ($actTypes as $act) {
                $menuCategories[] = [
                    'label' => $act->name,
                    'url' => route('pris.category', ['category' => Str::slug($act->name)]) . '?legalАctТype=' . $act->id,
                    'slug' => Str::slug($act->name)
                ];
                $menuCategoriesArchive[] = [
                    'label' => $act->name,
                    'url' => route('pris.archive.category', ['category' => Str::slug($act->name)]) . '?legalАctТype=' . $act->id,
                    'slug' => Str::slug($act->name)
                ];
            }
        }

        $pageTitle = __('site.pris.page_title');
        $extraBreadCrumbs = [];
        if ($item->in_archive) {
            $extraBreadCrumbs = array(['name' => __('site.pris.archive'), 'url' => route('pris.archive')]);
        }
        if (isset($requestFilter['legalАctТype']) && $requestFilter['legalАctТype']) {
            $actType = LegalActType::with(['translations'])->find((int)$requestFilter['legalАctТype']);
            if ($actType) {
                $extraBreadCrumbs[] = ['name' => $actType->name, 'url' => ''];
            }
        }
        $hasSubscribeEmail = $this->hasSubscription($item);
        $hasSubscribeRss = false;

        $this->composeBreadcrumbs($extraBreadCrumbs, $item);
        $this->setSeo($item->mcDisplayName, '', '', array('title' => $item->mcDisplayName, 'img' => Pris::DEFAULT_IMG));

        return $this->view('site.pris.view',
            compact('item', 'pageTitle', 'pageTopContent', 'menuCategories', 'menuCategoriesArchive', 'hasSubscribeEmail', 'hasSubscribeRss')
        );
    }

    private function sorters()
    {
        return array(
            'category' => ['class' => 'col-md-3', 'label' => trans_choice('custom.category', 1)],
//            'importer' => ['class' => 'col-md-3', 'label' => trans_choice('custom.institutions', 1)],
            'docDate' => ['class' => 'col-md-3', 'label' => __('custom.date')],
            'docNum' => ['class' => 'col-md-3', 'label' => __('custom.number')],
        );
    }

    private function filters($request, $is_in_ip_range)
    {
        return array(
            'legalActTypes' => array(
                'type' => 'select',
                'options' => optionsFromModel(LegalActType::optionsListForPrisSearch(!$is_in_ip_range), true),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.legal_act_types', 1),
                'value' => $request->input('legalActTypes'),
                'col' => 'col-md-12'
            ),
            'fullSearch' => array(
                'type' => 'text',
                'label' => __('custom.files') . '/' . __('custom.pris_about') . '/' . __('custom.pris_legal_reason') . '/' . trans_choice('custom.tags', 2),
                'value' => $request->input('fullSearch'),
                'col' => 'col-md-12'
            ),
            'formGroup' => array(
                'title' => __('custom.search_in'),
                'class' => '',
                'fields' => array(
                    'fileSearch' => array(
                        'type' => 'checkbox',
                        'checked' => $request->ajax() ? $request->input('fileSearch') : true,
                        'label' => trans_choice('custom.files', 2),
                        'value' => 1,
                        'col' => 'col-md-1 d-inline me-2'
                    ),
                    'importer' => array(
                        'type' => 'checkbox',
                        'checked' => $request->ajax() ? $request->input('importer') : true,
                        'label' => trans_choice('custom.importers', 1),
                        'value' => 1,
                        'col' => 'col-md-1 d-inline me-2'
                    ),
                    'aboutSearch' => array(
                        'type' => 'checkbox',
                        'checked' => $request->ajax() ? $request->input('aboutSearch') : true,
                        'label' => __('custom.about'),
                        'value' => 1,
                        'col' => 'col-md-1 d-inline me-2'
                    ),
                    'legalReasonSearch' => array(
                        'type' => 'checkbox',
                        'checked' => $request->ajax() ? $request->input('legalReasonSearch') : true,
                        'label' => __('custom.pris_legal_reason'),
                        'value' => 1,
                        'col' => 'col-md-1 d-inline me-2'
                    ),
                    'tagsSearch' => array(
                        'type' => 'checkbox',
                        'checked' => $request->ajax() ? $request->input('tagsSearch') : true,
                        'label' => trans_choice('custom.tags', 2),
                        'value' => 1,
                        'col' => 'col-md-1 d-inline me-2'
                    )
                )
            ),
            'formGroupAndOrKeyword' => array(
                'title' => __('custom.criteria') . ':',
                'class' => 'mb-4',
                'fields' => array(
                    'fullKeyword' => array(
                        'type' => 'checkbox',
                        'checked' => $request->ajax() ? $request->input('fullKeyword') : false,
                        'label' => __('custom.full_keyword'),
                        'value' => 1,
                        'col' => 'col-md-1 d-inline me-2'
                    ),
                    'upperLowerCase' => array(
                        'type' => 'checkbox',
                        'checked' => $request->ajax() ? $request->input('upperLowerCase') : false,
                        'label' => __('custom.upper_lower_case'),
                        'value' => 1,
                        'col' => 'col-md-1 d-inline me-2'
                    ),
                    'logicalАnd' => array(
                        'type' => 'checkbox',
                        'checked' => $request->ajax() ? $request->input('logicalАnd') : false,
                        'label' => __('custom.logical_and'),
                        'value' => 1,
                        'col' => 'col-md-1 d-inline me-2'
                    ),
                )
            ),
            'docNum' => array(
                'type' => 'text',
                'label' => __('custom.document_number'),
                'value' => $request->input('docNum'),
                'col' => 'col-md-3'
            ),
            'year' => array(
                'type' => 'datepicker-year',
                'label' => __('custom.year'),
                'value' => $request->input('year'),
                'col' => 'col-md-3'
            ),
            'docDate' => array(
                'type' => 'datepicker',
                'value' => $request->input('docDate'),
                'label' => __('custom.date'),
                'col' => 'col-md-3'
            ),
//            'institutions' => array(
//                'type' => 'subjects',
//                'label' => trans_choice('custom.institutions', 1),
//                'multiple' => true,
//                'options' => optionsFromModel(Institution::simpleOptionsList(), true, '', trans_choice('custom.institutions', 1)),
//                'value' => request()->input('institutions'),
//                'default' => '',
//                'col' => 'col-md-3'
//            ),
//            'importer' => array(
//                'type' => 'text',
//                'label' => trans_choice('custom.importers', 1),
//                'value' => $request->input('importer'),
//                'col' => 'col-md-4'
//            ),
//            'formGroupInstitution' => array(
//                'title' => __('custom.criteria') . ':',
//                'class' => '',
//                'fields' => array(
//                    'institutionFullSearch' => array(
//                        'type' => 'checkbox',
//                        'checked' => $request->ajax() ? $request->input('institutionFullSearch') : false,
//                        'label' => __('custom.full_keyword'),
//                        'value' => 1,
//                        'col' => 'col-md-1 d-inline me-2'
//                    ),
//                    'institutionUpperLowerCase' => array(
//                        'type' => 'checkbox',
//                        'checked' => $request->ajax() ? $request->input('institutionUpperLowerCase') : false,
//                        'label' => __('custom.upper_lower_case'),
//                        'value' => 1,
//                        'col' => 'col-md-1 d-inline me-2'
//                    ),
//                )
//            ),
            'formGroupPeriod' => array(
                'title' => __('custom.period_search'),
                'class' => 'row',
                'fields' => array(
                    'fromDate' => array(
                        'type' => 'datepicker',
                        'value' => $request->input('fromDate'),
                        'label' => __('custom.begin_date'),
                        'col' => 'col-md-3'
                    ),
                    'toDate' => array(
                        'type' => 'datepicker',
                        'value' => $request->input('toDate'),
                        'label' => __('custom.end_date'),
                        'col' => 'col-md-3'
                    ),
                ),
            ),
            'newspaperNumber' => array(
                'type' => 'text',
                'label' => __('custom.newspaper_number'),
                'value' => $request->input('newspaperNumber'),
                'col' => 'col-md-4'
            ),
            'newspaperYear' => array(
                'type' => 'text',
                'label' => __('custom.newspaper_year'),
                'value' => $request->input('newspaperYear'),
                'col' => 'col-md-4'
            ),
            'changes' => array(
                'type' => 'text',
                'label' => __('custom.change_docs'),
                'value' => $request->input('changes'),
                'col' => 'col-md-4'
            ),
            'paginate' => array(
                'type' => 'select',
                'options' => paginationSelect(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.filter_pagination'),
                'value' => $request->input('paginate') ?? config('app.default_paginate'),
                'col' => 'col-md-3'
            ),

        );
    }

    /**
     * @param array $extraItems
     * @param $item
     * @return void
     */
    private function composeBreadcrumbs(array $extraItems = [], $item = null)
    {
        $customBreadcrumbs = array(
            ['name' => __('site.menu.pris'), 'url' => route('pris.index')]
        );
        if (!empty($extraItems)) {
            foreach ($extraItems as $eItem) {
                $customBreadcrumbs[] = $eItem;
            }
        }

        if ($item) {
            $customBreadcrumbs[] = ['name' => $item->mcDisplayName, 'url' => ''];
        }
        $this->setBreadcrumbsFull($customBreadcrumbs);
    }
}
