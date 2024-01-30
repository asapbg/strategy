<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    public function show($slug) {
        $item = Page::with(['translation', 'files'])->where('slug', $slug)->first();
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $pageTitle = $item->name;
        $this->setSeo($item->meta_title, $item->meta_description, $item->meta_keyword);
        $this->setBreadcrumbsFull(array(
            ['name' => $item->name, 'url' => '']
        ));
        return $this->view('site.page', compact('item', 'pageTitle'));
    }
}
