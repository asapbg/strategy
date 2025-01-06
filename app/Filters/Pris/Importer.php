<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Importer extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        $value = ""; // stop using this as it was in separate field and now is not
        if (!empty($value)) {
            $value = "'%$value%'";
            $importerColumn = 'importer::text';
            $oldImporterColumn = 'pris.old_importers';
            $condition = 'LIKE';

            if (!isset($filter['upperLowerCase'])) {
                $value = "UPPER($value)";
                $importerColumn = "UPPER($importerColumn)";
                $oldImporterColumn = "UPPER($oldImporterColumn)";
            }

            if (isset($filter['fullKeyword'])) {
                $condition = '=';
                $value = str_replace('%', '', $value);
            }

            $locale = app()->getLocale();
            $this->query->whereRaw("(
                $oldImporterColumn $condition $value
                OR exists (select * from pris_translations where pris.id = pris_translations.pris_id and locale = '$locale' and $importerColumn $condition $value)
            )");
        }
    }
}

