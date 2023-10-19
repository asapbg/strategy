<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;

class ImpactPageController extends PageController
{
    const LIST_ROUTE = 'admin.impact_pages.index';
    const EDIT_ROUTE = 'admin.impact_pages.edit';
    const STORE_ROUTE = 'admin.impact_pages.store';
    const LIST_VIEW = 'admin.pages.index';
    const EDIT_VIEW = 'admin.pages.edit';
    const PAGE_TYPE = Page::TYPE_IMPACT_ASSESSMENT;
    const MODEL_NAME = 'custom.impact_assessment';
}
