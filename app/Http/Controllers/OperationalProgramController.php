<?php

namespace App\Http\Controllers;

use App\Models\Consultations\OperationalProgram;
use App\Models\StrategicDocuments\Institution;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OperationalProgramController extends Controller
{
    public function index(Request $request)
    {
        $paginate = $filter['paginate'] ?? OperationalProgram::PAGINATE;
        $items = OperationalProgram::FilterBy($request->all())->paginate($paginate);
        $pageTitle = __('site.menu.op');
        $this->setBreadcrumbsTitle($pageTitle);
        return $this->view('site.op.index', compact('items', 'pageTitle'));
    }

    public function show(Request $request, int $id = 0)
    {
        $item = OperationalProgram::find($id);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $pageTitle = trans_choice('custom.operational_program', 2).' '.$item->name;
        $this->setBreadcrumbsTitle($pageTitle);
        $data = $item->getTableData();
        $months = $item->id ? extractMonths($item->from_date,$item->to_date, false) : [];
        $institutions = Institution::simpleOptionsList()->pluck('name', 'id')->toArray();
        return $this->view('site.op.view', compact('item', 'data', 'months', 'institutions'));
    }
}
