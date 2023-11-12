<?php

namespace App\Http\Controllers;

use App\Models\Pris;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrisController extends Controller
{
    public function index()
    {
        $items = Pris::with(['translation'])->get();
        $pageTitle = __('site.menu.public_consultation');
        $this->setBreadcrumbsTitle($pageTitle);
        return $this->view('site.pris.index', compact('items', 'pageTitle'));
    }

    public function show(Request $request, int $id = 0)
    {
        $item = Pris::with(['translation'])->find($id);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $pageTitle = $item->title;
        $this->setBreadcrumbsTitle($pageTitle);
        return $this->view('site.pris.view', compact('item', 'pageTitle'));
    }
}
