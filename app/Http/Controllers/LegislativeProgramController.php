<?php

namespace App\Http\Controllers;

use App\Models\Consultations\LegislativeProgram;
use App\Models\LegalActType;
use App\Models\Setting;
use App\Models\StrategicDocuments\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LegislativeProgramController extends Controller
{
    public function index(Request $request)
    {
        $paginate = $filter['paginate'] ?? LegislativeProgram::PAGINATE;
        $items = LegislativeProgram::Published()->FilterBy($request->all())->orderBy('from_date', 'desc')->paginate($paginate);
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_LP.'_'.app()->getLocale())->first();
        $pageTitle = __('site.menu.lp');

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

        return $this->view('site.lp.index', compact('items', 'pageTitle', 'pageTopContent', 'menuCategories'));
    }

    public function show(Request $request, int $id = 0)
    {
//        return $this->view('templates.zp');
        $item = LegislativeProgram::Published()->with(['rowFiles'])->find($id);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $pageTitle = trans_choice('custom.legislative_program', 1).' '.$item->name;
        $this->setBreadcrumbsTitle($pageTitle);
        $data = $item->getTableData();
        $months = $item->id ? extractMonths($item->from_date,$item->to_date, false) : [];
        $institutions = Institution::simpleOptionsList()->pluck('name', 'id')->toArray();
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_LP.'_'.app()->getLocale())->first();
        return $this->view('site.lp.view', compact('item', 'months', 'data', 'institutions', 'pageTitle', 'pageTopContent'));
    }
}
