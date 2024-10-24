<?php

namespace App\Http\Controllers;

use App\Models\ActType;
use App\Models\Consultations\PublicConsultation;
use App\Models\LegalActType;
use App\Models\Pris;
use App\Models\Setting;
use App\Models\StrategicDocuments\Institution;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PrisController extends Controller
{
    public function index(Request $request, $category = '')
    {
        $rssUrl = config('feed.feeds.pris.url');

        //Filter
        $rf = $request->all();
        $requestFilter = $request->all();
        if( isset($requestFilter['legalАctТype']) && !empty($requestFilter['legalАctТype']) && !empty($category)) {
            $actType = LegalActType::with(['translations'])->find((int)$requestFilter['legalАctТype']);
            if( $actType && Str::slug($actType->name) != $category ){
                return redirect(route('pris.index', $request->query()));
            }
        }

        $filter = $this->filters($request);

        //Sorter
        $sorter = $this->sorters();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'docDate';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');

        $paginate = $requestFilter['paginate'] ?? config('app.default_paginate');
        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        $tagSearch = isset($requestFilter['fullSearch']) && !empty($requestFilter['fullSearch']) ? $requestFilter['fullSearch'] : null;
        $items = Pris::select('pris.*')
            ->NotInArchive()
            ->LastVersion()
            ->InPris()
            ->Published()
            ->with(['translations', 'actType', 'actType.translations', 'institutions', 'institutions.translation'])
            ->leftJoin('pris_institution', 'pris_institution.pris_id', '=', 'pris.id')
            ->leftJoin('pris_translations', function ($j){
                $j->on('pris_translations.pris_id', '=', 'pris.id')
                    ->where('pris_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('institution', 'institution.id', '=', 'pris_institution.institution_id')
            ->leftJoin('institution_translations', function ($j){
                $j->on('institution_translations.institution_id', '=', 'institution.id')
                    ->where('institution_translations.locale', '=', app()->getLocale());
            })
            ->join('legal_act_type', 'legal_act_type.id', '=', 'pris.legal_act_type_id')
            ->join('legal_act_type_translations', function ($j){
                $j->on('legal_act_type_translations.legal_act_type_id', '=', 'legal_act_type.id')
                    ->where('legal_act_type_translations.locale', '=', app()->getLocale());
            })
//            ->leftJoin('pris_tag', 'pris_tag.pris_id', '=', 'pris.id')
//            ->leftJoin('tag', 'pris_tag.tag_id', '=', 'tag.id')
//            ->leftJoin('tag_translations', function ($j){
//                $j->on('tag_translations.tag_id', '=', 'tag.id')
//                    ->where('tag_translations.locale', '=', app()->getLocale());
//            })
            ->where('pris.legal_act_type_id', '<>', LegalActType::TYPE_ARCHIVE)
            ->FilterBy($requestFilter)
            ->SortedBy($sort,$sortOrd)
            //->GroupBy('pris.id', 'institution_translations.name', 'legal_act_type_translations.name')
            ->paginate($paginate);


        $hasSubscribeEmail = $this->hasSubscription(null, Pris::class, $requestFilter);
        $hasSubscribeRss = false;

        $closeSearchForm = true;
        if( $request->ajax() ) {
            $closeSearchForm = false;
            return view('site.pris.list', compact('filter','sorter', 'items', 'rf','hasSubscribeEmail', 'hasSubscribeRss', 'requestFilter', 'rssUrl', 'closeSearchForm'));
        }

        $menuCategories = [];
        $menuCategoriesArchive = [];
        $actTypes = LegalActType::with(['translations'])
            ->Pris()
            ->where('id', '<>', LegalActType::TYPE_ORDER)
            ->where('id', '<>', LegalActType::TYPE_ARCHIVE)
            ->get();
        if( $actTypes->count() ) {
            foreach ($actTypes as $act) {
                $menuCategories[] = [
                    'label' => $act->name,
                    'url' => route('pris.category', ['category' => Str::slug($act->name)]).'?legalАctТype='.$act->id,
                    'slug' => Str::slug($act->name)
                ];
                $menuCategoriesArchive[] = [
                    'label' => $act->name,
                    'url' => route('pris.archive.category', ['category' => Str::slug($act->name)]).'?legalАctТype='.$act->id,
                    'slug' => Str::slug($act->name)
                ];
            }
        }
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_PRIS.'_'.app()->getLocale())->first();

        $pageTitle = __('site.pris.page_title');
        $extraBreadCrumbs = [];
        if(isset($requestFilter['legalАctТype']) && $requestFilter['legalАctТype']) {
            $actType = LegalActType::with(['translations'])->find((int)$requestFilter['legalАctТype']);
            if($actType) {
                $extraBreadCrumbs[] = ['name' => $actType->name, 'url' => ''];
            }
        }
        $this->composeBreadcrumbs($extraBreadCrumbs);
        return $this->view('site.pris.index', compact('filter','sorter', 'items', 'pageTitle', 'menuCategories', 'menuCategoriesArchive', 'pageTopContent', 'rf', 'defaultOrderBy', 'defaultDirection', 'hasSubscribeEmail', 'hasSubscribeRss', 'requestFilter', 'rssUrl', 'closeSearchForm'));
    }

    public function archive(Request $request, $category = '')
    {
        //Filter
        $rf = $request->all();
        $requestFilter = $request->all();
        if( isset($requestFilter['legalАctТype']) && !empty($requestFilter['legalАctТype']) && !empty($category)) {
            $actType = LegalActType::with(['translations'])->find((int)$requestFilter['legalАctТype']);
            if( $actType && Str::slug($actType->name) != $category ){
                return redirect(route('pris.index', $request->query()));
            }
        }

        $filter = $this->filters($request);

        //Sorter
        $sorter = $this->sorters();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'docDate';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');

        $paginate = $requestFilter['paginate'] ?? config('app.default_paginate');
        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        $tagSearch = isset($requestFilter['fullSearch']) && !empty($requestFilter['fullSearch']) ? $requestFilter['fullSearch'] : null;
        $items = Pris::select('pris.*')
            ->InArchive()
            ->LastVersion()
            ->InPris()
            ->Published()
            ->with(['translations', 'actType', 'actType.translations', 'institutions', 'institutions.translation'])
            ->leftJoin('pris_institution', 'pris_institution.pris_id', '=', 'pris.id')
            ->leftJoin('pris_translations', function ($j){
                $j->on('pris_translations.pris_id', '=', 'pris.id')
                    ->where('pris_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('institution', 'institution.id', '=', 'pris_institution.institution_id')
            ->leftJoin('institution_translations', function ($j){
                $j->on('institution_translations.institution_id', '=', 'institution.id')
                    ->where('institution_translations.locale', '=', app()->getLocale());
            })
            ->join('legal_act_type', 'legal_act_type.id', '=', 'pris.legal_act_type_id')
            ->join('legal_act_type_translations', function ($j){
                $j->on('legal_act_type_translations.legal_act_type_id', '=', 'legal_act_type.id')
                    ->where('legal_act_type_translations.locale', '=', app()->getLocale());
            })
//            ->leftJoin('pris_tag', 'pris_tag.pris_id', '=', 'pris.id')
//            ->leftJoin('tag', 'pris_tag.tag_id', '=', 'tag.id')
//            ->leftJoin('tag_translations', function ($j){
//                $j->on('tag_translations.tag_id', '=', 'tag.id')
//                    ->where('tag_translations.locale', '=', app()->getLocale());
//            })
            ->where('pris.legal_act_type_id', '<>', LegalActType::TYPE_ARCHIVE)
            ->FilterBy($requestFilter)
            ->SortedBy($sort,$sortOrd)
            //->GroupBy('pris.id', 'institution_translations.name', 'legal_act_type_translations.name')
            ->paginate($paginate);


        $hasSubscribeEmail = $this->hasSubscription(null, Pris::class, $requestFilter);
        $hasSubscribeRss = false;
        $no_rss = true;
        $no_email_subscribe = true;

        if( $request->ajax() ) {
            return view('site.pris.list', compact('filter','sorter', 'items', 'rf','hasSubscribeEmail',
                'hasSubscribeRss', 'requestFilter', 'no_rss', 'no_email_subscribe'));
        }

        $menuCategories = [];
        $menuCategoriesArchive = [];
        $actTypes = LegalActType::with(['translations'])
            ->Pris()
            ->where('id', '<>', LegalActType::TYPE_ORDER)
            ->where('id', '<>', LegalActType::TYPE_ARCHIVE)
            ->get();
        if( $actTypes->count() ) {
            foreach ($actTypes as $act) {
                $menuCategories[] = [
                    'label' => $act->name,
                    'url' => route('pris.category', ['category' => Str::slug($act->name)]).'?legalАctТype='.$act->id,
                    'slug' => Str::slug($act->name)
                ];
                $menuCategoriesArchive[] = [
                    'label' => $act->name,
                    'url' => route('pris.archive.category', ['category' => Str::slug($act->name)]).'?legalАctТype='.$act->id,
                    'slug' => Str::slug($act->name)
                ];
            }
        }
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_PRIS.'_'.app()->getLocale())->first();

        $pageTitle = __('site.menu.pris');
        $extraBreadCrumbs = array(['name' => __('site.pris.archive'), 'url' => route('pris.archive')]);
        if(isset($requestFilter['legalАctТype']) && $requestFilter['legalАctТype']) {
            $actType = LegalActType::with(['translations'])->find((int)$requestFilter['legalАctТype']);
            if($actType) {
                $extraBreadCrumbs[] = ['name' => $actType->name, 'url' => ''];
            }
        }
        $this->composeBreadcrumbs($extraBreadCrumbs);
        return $this->view('site.pris.index', compact('filter','sorter', 'items', 'pageTitle', 'menuCategories',
            'menuCategoriesArchive', 'pageTopContent', 'rf', 'defaultOrderBy', 'defaultDirection', 'hasSubscribeEmail', 'hasSubscribeRss',
            'requestFilter', 'no_rss', 'no_email_subscribe'));
    }

    public function show(Request $request, $category, int $id = 0)
    {
        $item = Pris::LastVersion()->InPris()->Published()->with(['translation', 'actType', 'actType.translation', 'institution', 'institution.translation',
            'tags', 'tags.translation', 'changedDocs',
            'changedDocs.actType', 'changedDocs.actType.translation',
            'changedDocs.institution', 'changedDocs.institution.translation', 'files'])->find($id);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }

//        $pageTitle = $item->mcDisplayName;
//        $this->setBreadcrumbsTitle($pageTitle);
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_PRIS.'_'.app()->getLocale())->first();

        $menuCategories = [];
        $menuCategoriesArchive = [];
        $actTypes = LegalActType::where('id', '<>', LegalActType::TYPE_ORDER)
            ->Pris()
            ->where('id', '<>', LegalActType::TYPE_ARCHIVE)
            ->get();
        if( $actTypes->count() ) {
            foreach ($actTypes as $act) {
                $menuCategories[] = [
                    'label' => $act->name,
                    'url' => route('pris.category', ['category' => Str::slug($act->name)]).'?legalАctТype='.$act->id,
                    'slug' => Str::slug($act->name)
                ];
                $menuCategoriesArchive[] = [
                    'label' => $act->name,
                    'url' => route('pris.archive.category', ['category' => Str::slug($act->name)]).'?legalАctТype='.$act->id,
                    'slug' => Str::slug($act->name)
                ];
            }
        }

        $pageTitle = __('site.pris.page_title');
        $extraBreadCrumbs = [];
//        if($item->actType) {
//            $extraBreadCrumbs[] = ['name' => $item->actType->name, 'url' => route('pris.category', ['category' => Str::slug($item->actType->name)]).'?legalАctТype='.$item->actType->id];
//        }
        if($item->in_archive){
            $extraBreadCrumbs = array(['name' => __('site.pris.archive'), 'url' => route('pris.archive')]);
        }
        if(isset($requestFilter['legalАctТype']) && $requestFilter['legalАctТype']) {
            $actType = LegalActType::with(['translations'])->find((int)$requestFilter['legalАctТype']);
            if($actType) {
                $extraBreadCrumbs[] = ['name' => $actType->name, 'url' => ''];
            }
        }
        $hasSubscribeEmail = $this->hasSubscription($item);
        $hasSubscribeRss = false;

        $this->composeBreadcrumbs($extraBreadCrumbs, $item);
        $this->setSeo($item->mcDisplayName, '', '', array('title' => $item->mcDisplayName, 'img' => Pris::DEFAULT_IMG));

        return $this->view('site.pris.view', compact('item', 'pageTitle', 'pageTopContent', 'menuCategories', 'menuCategoriesArchive', 'hasSubscribeEmail', 'hasSubscribeRss'));
    }

    private function sorters()
    {
        return array(
            'category' => ['class' => 'col-md-3', 'label' => trans_choice('custom.category', 1)],
            'importer' => ['class' => 'col-md-3', 'label' => trans_choice('custom.institutions', 1)],
            'docDate' => ['class' => 'col-md-3', 'label' => __('custom.date')],
            'docNum' => ['class' => 'col-md-3', 'label' => __('custom.number')],
        );
    }

    private function filters($request)
    {
        return array(
            'legalActTypes' => array(
                'type' => 'select',
                'options' => optionsFromModel(LegalActType::optionsList(true), true),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.legal_act_types', 1),
                'value' => $request->input('legalActTypes'),
                'col' => 'col-md-12'
            ),
            'fullSearch' => array(
                'type' => 'text',
                'label' => __('custom.files').'/'.__('custom.pris_about').'/'.__('custom.pris_legal_reason').'/'.trans_choice('custom.tags', 2),
                'value' => $request->input('fullSearch'),
                'col' => 'col-md-12'
            ),
            'formGroup' => array(
                'title' => __('custom.search_in'),
                'class' => 'mb-4',
                'fields' => array(
                    'fileSearch' => array(
                        'type' => 'checkbox',
                        'checked' => $request->ajax() ? $request->input('fileSearch') : true,
                        'label' => trans_choice('custom.files', 2),
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
                'type' => 'text',
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
            'institutions' => array(
                'type' => 'subjects',
                'label' => trans_choice('custom.institutions', 1),
                'multiple' => true,
                'options' => optionsFromModel(Institution::simpleOptionsList(), true, '', trans_choice('custom.institutions', 1)),
                'value' => request()->input('institutions'),
                'default' => '',
                'col' => 'col-md-3'
            ),
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
    private function composeBreadcrumbs(array $extraItems = [], $item = null){
        $customBreadcrumbs = array(
            ['name' => __('site.menu.pris'), 'url' => route('pris.index')]
        );
        if(!empty($extraItems)){
            foreach ($extraItems as $eItem){
                $customBreadcrumbs[] = $eItem;
            }
        }

        if($item){
            $customBreadcrumbs[] = ['name' => $item->mcDisplayName, 'url' => ''];
        }
        $this->setBreadcrumbsFull($customBreadcrumbs);
    }
}
