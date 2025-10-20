<?php

namespace App\Http\Controllers;

use App\Models\Consultations\OperationalProgram;
use App\Models\LegalActType;
use App\Models\Page;
use App\Models\Setting;
use App\Models\StrategicDocuments\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class OperationalProgramController extends Controller
{
    public function index(Request $request)
    {
        $can_access_orders = $this->canAccessOrders($request);
        $rssUrl = config('feed.feeds.op.url');
        $paginate = $filter['paginate'] ?? OperationalProgram::PAGINATE;
        $items = OperationalProgram::Published()->FilterBy($request->all())->orderBy('from_date', 'desc')->paginate($paginate);
        $infoPage = Page::where('system_name', '=', Page::OP_INFO)->first();
        $pageTopContent = $infoPage ? $infoPage->content : null;

        //$pageTitle = __('site.menu.op');

        [ $menuCategories, $menuCategoriesArchive ] = $this->getPrisProgramsMenuItems($can_access_orders);

        $pageTitle = __('site.pris.page_title');
        $this->composeBreadcrumbs();

        $hasSubscribeEmail = $this->hasSubscription(null, OperationalProgram::class, $request->all());
        $hasSubscribeRss = false;
        return $this->view('site.op.index', compact('items', 'pageTitle', 'pageTopContent', 'menuCategories', 'menuCategoriesArchive', 'rssUrl', 'hasSubscribeEmail', 'hasSubscribeRss'));
    }

    public function show(Request $request, int $id = 0)
    {
        $can_access_orders = $this->canAccessOrders($request);
//        return $this->view('templates.op');
        $item = OperationalProgram::Published()->with(['rowFilesLocale'])->find($id);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
//        $pageTitle = $item->name;
//        $this->setBreadcrumbsTitle($pageTitle);
        $data = $item->getTableData();
        $months = $item->id ? extractMonths($item->from_date,$item->to_date, false) : [];
        $institutions = Institution::simpleOptionsList()->pluck('name', 'id')->toArray();
        //$pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_OP.'_'.app()->getLocale())->first();
        $pageTitle = __('site.pris.page_title');
        $this->composeBreadcrumbs([], $item);

        [ $menuCategories, $menuCategoriesArchive ] = $this->getPrisProgramsMenuItems($can_access_orders);

        $hasSubscribeEmail = $this->hasSubscription($item);
        $hasSubscribeRss = false;
        return $this->view('site.op.view', compact('item', 'data', 'months', 'institutions', 'pageTitle', 'hasSubscribeEmail', 'hasSubscribeRss', 'menuCategories', 'menuCategoriesArchive'));
    }

    /**
     * @param array $extraItems
     * @param $item
     * @return void
     */
    private function composeBreadcrumbs(array $extraItems = [], $item = null){
        $customBreadcrumbs = array(
            ['name' => __('site.menu.pris'), 'url' => route('pris.index')],
            ['name' => __('site.planning'), 'url' => ''],
            ['name' => trans_choice('custom.operational_programs',1), 'url' => route('op.index')]
        );
        if(!empty($extraItems)){
            foreach ($extraItems as $eItem){
                $customBreadcrumbs[] = $eItem;
            }
        }

        if($item){
            $customBreadcrumbs[] = ['name' => $item->name, 'url' => ''];
        }
        $this->setBreadcrumbsFull($customBreadcrumbs);
    }
}
