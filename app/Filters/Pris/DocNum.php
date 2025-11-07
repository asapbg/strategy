<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class DocNum extends QueryFilter implements FilterContract
{
    public function handle($value, $filter = null): void
    {
        $value = mb_strtolower($value);
        if (!empty($value)) {
            $this->query->whereRaw("LOWER(TRIM(pris.doc_num)) = '$value'");
        }
    }
}

