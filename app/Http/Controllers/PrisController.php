<?php

namespace App\Http\Controllers;

use App\Models\Pris;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrisController extends Controller
{
    public function index(Request $request)
    {
//        return $this->view('templates.8_2_1_1_2_public_legal_information');
        $paginate = $filter['paginate'] ?? Pris::PAGINATE;
        $items = Pris::with(['translation', 'actType', 'actType.translation', 'institution', 'institution.translation'])
            ->FilterBy($request->all())->paginate($paginate);
        $pageTitle = __('site.menu.pris');
        return $this->view('site.pris.index', compact('items', 'pageTitle'));
    }

    public function show(Request $request, int $id = 0)
    {
//        return $this->view('templates.pris-postanovlenie');
        $item = Pris::with(['translation', 'actType', 'actType.translation', 'institution', 'institution.translation',
            'tags', 'tags.translation', 'changedDocs',
            'changedDocs.actType', 'changedDocs.actType.translation',
            'changedDocs.institution', 'changedDocs.institution.translation'])->find($id);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $pageTitle = $item->actType->name.' '.__('custom.number_symbol').' '.$item->actType->doc_num.' '.__('custom.of').' '.$item->institution->name.' от '.$item->docYear.' '.__('site.year_short');
        $this->setBreadcrumbsTitle($pageTitle);
        return $this->view('site.pris.view', compact('item', 'pageTitle'));
    }
}
