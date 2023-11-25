<?php

namespace App\Http\Controllers;

use App\Models\Consultations\OperationalProgram;
use App\Models\Setting;
use App\Models\StrategicDocuments\Institution;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OperationalProgramController extends Controller
{
    public function index(Request $request)
    {
        $paginate = $filter['paginate'] ?? OperationalProgram::PAGINATE;
        $items = OperationalProgram::Published()->FilterBy($request->all())->paginate($paginate);
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_OP.'_'.app()->getLocale())->first();
        $pageTitle = __('site.menu.op');
        return $this->view('site.op.index', compact('items', 'pageTitle', 'pageTopContent'));
    }

    public function show(Request $request, int $id = 0)
    {
//        return $this->view('templates.op');
        $item = OperationalProgram::Published()->find($id);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $pageTitle = trans_choice('custom.operational_program', 1).' '.$item->name;
        $this->setBreadcrumbsTitle($pageTitle);
        $data = $item->getTableData();
        $months = $item->id ? extractMonths($item->from_date,$item->to_date, false) : [];
        $institutions = Institution::simpleOptionsList()->pluck('name', 'id')->toArray();
        return $this->view('site.op.view', compact('item', 'data', 'months', 'institutions', 'pageTitle'));
    }
}
