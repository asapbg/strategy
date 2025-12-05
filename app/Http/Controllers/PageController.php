<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    public function show($slug) {

        $item = Page::with(['translation', 'files'])->where('slug', $slug)->first();
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $pageTitle = $item->name;
        $this->setBreadcrumbsFull(array(
            ['name' => $item->name, 'url' => '']
        ));
        $this->setSeo(
            $item->meta_title ?? $item->name,
                $item->meta_description ?? $item->short_content,
            $item->meta_keyword,
            array('title' => $item->meta_title ?? $item->name, 'img' => Page::DEFAULT_IMG)
        );

        return $this->view('site.page', compact('item', 'pageTitle'));
    }
}
