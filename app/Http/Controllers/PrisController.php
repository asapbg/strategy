<?php

namespace App\Http\Controllers;

use App\Models\Pris;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrisController extends Controller
{
    public function index(Request $request)
    {
        $items = Pris::with(['translation', 'actType', 'actType.translation', 'institution', 'institution.translation'])->FilterBy($request->all())->get();
        $pageTitle = __('site.menu.pris');
        $this->setBreadcrumbsTitle($pageTitle);
        return $this->view('site.pris.index', compact('items', 'pageTitle'));
    }

    public function show(Request $request, int $id = 0)
    {
        $item = Pris::with(['translation', 'actType', 'actType.translation', 'institution', 'institution.translation',
            'tags', 'tags.translation', 'changedDocs',
            'changedDocs.actType', 'changedDocs.actType.translation',
            'changedDocs.institution', 'changedDocs.institution.translation'])->find($id);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $pageTitle = $item->actType->name.' '.__('custom.number_symbol').' '.$item->actType->doc_num.' '.__('custom.of').' '.$item->institution->name.' от '.$item->docYear.' '.__('site.year_short');
        $this->setBreadcrumbsTitle($pageTitle);
        return $this->view('site.pris.view', compact('item'));
    }
}