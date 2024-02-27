<?php

namespace App\Http\Controllers;

use App\Enums\PageModulesEnum;
use App\Enums\PublicationTypesEnum;
use App\Models\CustomRole;
use App\Models\Page;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OpenGovernmentPartnership extends Controller
{
    private $pageTitle;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->title_singular = __('custom.open_government_partnership');
        $this->pageTitle = __('custom.open_government_partnership');
    }
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $pageTitle = $this->pageTitle;
        $this->composeBreadcrumbs();
        return $this->view('site.ogp.index', compact('pageTitle'));

    }

    public function info()
    {
        $page = Page::with(['files' => function($q) {
            $q->where('locale', '=', app()->getLocale());
        }])
            ->where('system_name', '=', Page::OGP_INFO)
            ->first();
        if(!$page){
            abort(404);
        }
        $pageTitle = $this->pageTitle;
        $this->setSeo($page->meta_title, $page->meta_description, $page->meta_keyword);
        $this->composeBreadcrumbs(null, array(['name' => $page->name, 'url' => '']));
        return $this->view('site.ogp.page', compact('page', 'pageTitle'));
    }

    public function contacts(Request $request, $itemId = null)
    {
        $pageTitle = $this->pageTitle;
        $moderators = User::role([CustomRole::MODERATOR_PARTNERSHIP])->get();
        $this->composeBreadcrumbs(null, array(['name' => trans_choice('custom.contacts', 2), 'url' => '']));
        return $this->view('site.ogp.contacts', compact('moderators', 'pageTitle'));
    }

    public function libraryView(Request $request, $slug = ''){
        $page = Page::with(['files' => function($q) {
            $q->where('locale', '=', app()->getLocale());
        }])
            ->where('slug', '=', $slug)
            ->first();

        if(!$page){
            return back()->with('warning', __('custom.record_not_found'));
        }
        $pageTitle = $this->pageTitle;
        $this->setSeo($page->meta_title, $page->meta_description, $page->meta_keyword);
        $this->composeBreadcrumbs(null, array(
            ['name' => __('custom.library'), 'url' => ''],
            ['name' => $page->name, 'url' => '']
        ));

        $library = Page::with(['translations'])
            ->where('module_enum', '=', PageModulesEnum::MODULE_OGP->value)
            ->orderBy('order_idx', 'asc')
            ->get();
        return $this->view('site.ogp.page', compact('page', 'pageTitle', 'library'));
    }

    public function news(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->newsFilters($request, $requestFilter);
        //Sorter
        $sorter = $this->sorters();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'publishDate';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');

        $paginate = $requestFilter['paginate'] ?? Publication::PAGINATE;
        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        $pageTitle = $this->pageTitle;
        $items = Publication::select('publication.*')
            ->ActivePublic()
            ->with(['translations',])
            ->leftJoin('publication_translations', function ($j){
                $j->on('publication_translations.publication_id', '=', 'publication.id')
                    ->where('publication_translations.locale', '=', app()->getLocale());
            })
            ->FilterBy($requestFilter)
            ->where('publication.type', PublicationTypesEnum::TYPE_OGP_NEWS->value)
            ->SortedBy($sort,$sortOrd)
            ->GroupBy('publication.id', 'publication_translations.id')
            ->paginate($paginate);

        if( $request->ajax() ) {
            return view('site.ogp.main_news_list', compact('filter','sorter', 'items'));
        }

        $this->composeBreadcrumbs(null, array(['name' => trans_choice('custom.news', 2), 'url' => '']));
        return $this->view('site.ogp.main_news', compact('filter','sorter', 'items', 'defaultOrderBy', 'defaultDirection', 'pageTitle'));
    }

    public function newsDetails(Request $request, Publication $item){
        $pageTitle = $this->pageTitle;
        $this->setSeo($item->meta_title, $item->meta_description, $item->meta_keyword);
        $publication = $item;
        $this->composeBreadcrumbs(null, array(
            ['name' => trans_choice('custom.news', 2), 'url' => route('ogp.news')],
            ['name' => $item->title, 'url' => '']
        ));
        return $this->view('site.ogp.main_news_details', compact('publication', 'pageTitle'));
    }

    /**
     * @param $item
     * @param $extraItems
     * @return void
     */
    private function composeBreadcrumbs($item = null, $extraItems = []){
        $customBreadcrumbs = array(
            ['name' => __('custom.open_government_partnership'), 'url' => route('ogp.list')]
        );

//        if($item){
//            $customBreadcrumbs[] = ['name' => $item->name, 'url' => !empty($extraItems) ? route('ogp.view', $item) : null];
//        }
        if(!empty($extraItems)){
            foreach ($extraItems as $eItem){
                $customBreadcrumbs[] = $eItem;
            }
        }
        $this->setBreadcrumbsFull($customBreadcrumbs);
    }

    private function sorters()
    {
        return array(
            'title' => ['class' => 'col-md-3', 'label' => __('custom.title')],
            'publishDate' => ['class' => 'col-md-3', 'label' => __('custom.date_published')],
        );
    }

    private function newsFilters($request, $currentFilter)
    {
        return array(
            'titleContent' => array(
                'type' => 'text',
                'label' => __('custom.title_content'),
                'value' => $request->input('titleContent'),
                'col' => 'col-md-4'
            ),
            'from' => array(
                'type' => 'datepicker',
                'value' => $request->input('from'),
                'label' => __('custom.from_date'),
                'col' => 'col-md-4'
            ),
            'to' => array(
                'type' => 'datepicker',
                'value' => $request->input('to'),
                'label' => __('custom.to_date'),
                'col' => 'col-md-4'
            ),
            'paginate' => array(
                'type' => 'select',
                'options' => paginationSelect(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.filter_pagination'),
                'value' => $request->input('paginate') ?? Publication::PAGINATE,
                'col' => 'col-md-3'
            ),
        );
    }
}
