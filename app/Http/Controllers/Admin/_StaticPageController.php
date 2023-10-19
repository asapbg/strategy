<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;

class StaticPageController extends PageController
{
    const LIST_ROUTE = 'admin.static_pages.index';
    const EDIT_ROUTE = 'admin.static_pages.edit';
    const STORE_ROUTE = 'admin.static_pages.store';
    const LIST_VIEW = 'admin.pages.index';
    const EDIT_VIEW = 'admin.pages.edit';
    const PAGE_TYPE = Page::TYPE_STATIC_PAGE;
    const MODEL_NAME = 'custom.static_pages';
}
