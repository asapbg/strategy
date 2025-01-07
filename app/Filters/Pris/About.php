<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class About extends QueryFilter implements FilterContract
{

    public function handle($value, $filter = null): void
    {
        if (!empty($value)) {

            $logicalAnd = is_array($filter) && isset($filter['logicalАnd']) ? "AND" : "OR";
            $fullKeyword = is_array($filter) && isset($filter['fullKeyword']) ? true : null;
            $upperLowerCase = is_array($filter) && isset($filter['upperLowerCase']) ? true : null;
            $condition = $upperLowerCase ? 'LIKE' : 'ILIKE';

            $where = "pris_translations.about $condition '%$value%'";
            if ($fullKeyword) {
                $where = "(";
                $where .= "pris_translations.about $condition '% $value %'";
                $where .= " OR pris_translations.about $condition '% $value'";
                $where .= " OR pris_translations.about $condition '$value %'";
                $where .= ")";
            }

            if (strstr($value, ",")) {
                $tags = explode(",", $value);
                $where = "(";
                if ($logicalAnd == "OR") {
                    foreach ($tags as $key => $tag) {
                        $tag = trim($tag);
                        if ($key != 0) {
                            $where .= " OR ";
                        }
                        if ($fullKeyword) {
                            $where .= "(";
                            $where .= "pris_translations.about $condition '% $tag %'";
                            $where .= " OR pris_translations.about $condition '% $tag'";
                            $where .= " OR pris_translations.about $condition '$tag %'";
                            $where .= ")";
                        } else {
                            $where .= "pris_translations.about $condition '%$tag%'";
                        }
                    }
                } else {
                    foreach ($tags as $key => $tag) {
                        $tag = trim($tag);

                        if ($key != 0) {
                            $where .= " AND ";
                        }
                        if ($fullKeyword) {
                            $where .= "(";
                            $where .= "pris_translations.about $condition '% $tag %'";
                            $where .= " OR pris_translations.about $condition '% $tag'";
                            $where .= " OR pris_translations.about $condition '$tag %'";
                            $where .= ")";
                        } else {
                            $where .= "pris_translations.about $condition '%$tag%'";
                        }
                    }
                }
                $where .= ")";
            }
            //dd($where);

            $this->query->whereRaw($where);;
        }
    }
}

