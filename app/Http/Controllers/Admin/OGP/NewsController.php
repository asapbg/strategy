<?php

namespace App\Http\Controllers\Admin\OGP;

use App\Http\Controllers\Admin\PublicationController;
use App\Models\Publication;
use App\Models\PublicationCategory;

class NewsController extends PublicationController
{
    const LIST_ROUTE = 'admin.ogp.articles.index';
    const EDIT_ROUTE = 'admin.ogp.articles.edit';
    const STORE_ROUTE = 'admin.ogp.articles.store';
    const LIST_VIEW = 'admin.publications.index';
    const EDIT_VIEW = 'admin.publications.edit';
    const PUBLICATION_TYPE = Publication::TYPE_OGP_NEWS;
    const MODEL_NAME = 'custom.ogp.articles';

    public static function getCategories()
    {
        return collect([PublicationCategory::all()->first()]);
    }
}
