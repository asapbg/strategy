<?php

namespace App\Http\Controllers;

use App\Enums\PageModulesEnum;
use App\Models\CustomRole;
use App\Models\Page;
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
}
