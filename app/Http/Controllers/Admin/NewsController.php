<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\PublicationController;
use App\Models\NewsCategory;
use App\Models\Publication;

class NewsController extends PublicationController
{
    const LIST_ROUTE = 'admin.news.index';
    const EDIT_ROUTE = 'admin.news.edit';
    const STORE_ROUTE = 'admin.news.store';
    const LIST_VIEW = 'admin.publications.index';
    const EDIT_VIEW = 'admin.publications.edit';
    const PUBLICATION_TYPE = Publication::TYPE_NEWS;
    const MODEL_NAME = 'custom.news';

    public static function getCategories()
    {
        return NewsCategory::all();
    }

}
