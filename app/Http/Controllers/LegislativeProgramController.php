<?php

namespace App\Http\Controllers;

use App\Models\Consultations\LegislativeProgram;
use App\Models\Setting;
use App\Models\StrategicDocuments\Institution;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LegislativeProgramController extends Controller
{
    public function index(Request $request)
    {
        $paginate = $filter['paginate'] ?? LegislativeProgram::PAGINATE;
        $items = LegislativeProgram::Published()->FilterBy($request->all())->paginate($paginate);
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_LP.'_'.app()->getLocale())->first();
        $pageTitle = __('site.menu.lp');
        return $this->view('site.lp.index', compact('items', 'pageTitle', 'pageTopContent'));
    }

    public function show(Request $request, int $id = 0)
    {
//        return $this->view('templates.zp');
        $item = LegislativeProgram::Published()->with(['rowFiles'])->find($id);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $pageTitle = trans_choice('custom.operational_program', 1).' '.$item->name;
        $this->setBreadcrumbsTitle($pageTitle);
        $data = $item->getTableData();
        $months = $item->id ? extractMonths($item->from_date,$item->to_date, false) : [];
        $institutions = Institution::simpleOptionsList()->pluck('name', 'id')->toArray();
        return $this->view('site.lp.view', compact('item', 'months', 'data', 'institutions', 'pageTitle'));
    }
}
