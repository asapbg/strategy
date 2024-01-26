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
        $paginate = $filter['paginate'] ?? OperationalProgram::PAGINATE;
        $items = OperationalProgram::Published()->FilterBy($request->all())->orderBy('from_date', 'desc')->paginate($paginate);
        $infoPage = Page::where('system_name', '=', Page::OP_INFO)->first();
        $pageTopContent = $infoPage ? $infoPage->content : null;

        //$pageTitle = __('site.menu.op');

        $menuCategories = [];
        $actTypes = LegalActType::where('id', '<>', LegalActType::TYPE_ORDER)
            ->where('id', '<>', LegalActType::TYPE_ARCHIVE)
            ->get();
        if( $actTypes->count() ) {
            foreach ($actTypes as $act) {
                $menuCategories[] = [
                    'label' => $act->name,
                    'url' => route('pris.category', ['category' => Str::slug($act->name)]).'?legalАctТype='.$act->id,
                    'slug' => Str::slug($act->name)
                ];
            }
        }

        $pageTitle = __('site.pris.page_title');
        $this->composeBreadcrumbs();
        return $this->view('site.op.index', compact('items', 'pageTitle', 'pageTopContent', 'menuCategories'));
    }

    public function show(Request $request, int $id = 0)
    {
//        return $this->view('templates.op');
        $item = OperationalProgram::Published()->find($id);
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
        return $this->view('site.op.view', compact('item', 'data', 'months', 'institutions', 'pageTitle'));
    }

    /**
     * @param array $extraItems
     * @param $item
     * @return void
     */
    private function composeBreadcrumbs(array $extraItems = [], $item = null){
        $customBreadcrumbs = array(
            ['name' => __('site.menu.pris'), 'url' => route('pris.index')],
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
