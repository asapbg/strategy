<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Importer extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if (!empty($value)) {
            $locale = app()->getLocale();
            $this->query->whereRaw("(
                pris.old_importers ILIKE '%$value%'
                OR exists (select * from pris_translations where pris.id = pris_translations.pris_id and locale = '$locale' and importer::text ILIKE '%$value%')
            )");
        }
    }
}

