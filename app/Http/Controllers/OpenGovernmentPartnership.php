<?php

namespace App\Http\Controllers;

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
