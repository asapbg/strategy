<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Tags extends QueryFilter implements FilterContract
{
    public function handle($value, $filter = null): void
    {
        $logicalAnd = is_array($filter) && isset($filter['logicalАnd']) ? "AND" : "OR";
        $upperLowerCase = is_array($filter) && isset($filter['upperLowerCase']) ? true : null;
        $fullKeyword = is_array($filter) && isset($filter['fullKeyword']) ? true : null;
        $condition = $upperLowerCase ? 'LIKE' : 'ILIKE';
        $locale = app()->getLocale();

        $where = "tag_translations.label $condition '%$value%'";
        if ($fullKeyword) {
            $where = "tag_translations.label $condition '$value'";
        }
        $trimmed_tags = "";
        if (strstr($value, ",")) {
            $tags = explode(",", $value);
            $tags_count = count($tags);
            if ($logicalAnd == "OR") {
                $where = "(";
                foreach ($tags as $key => $tag) {
                    $tag = trim($tag);
                    if ($key === 0) {
                        $where .= $upperLowerCase ? "tag_translations.label = '$tag'" : "LOWER(tag_translations.label) = '".mb_strtolower($tag)."'";
                    } else {
                        $where .= $upperLowerCase ? " OR tag_translations.label = '$tag'" : " OR LOWER(tag_translations.label) = '".mb_strtolower($tag)."'";
                    }
                }
                $where .= " )";
            } else {
                foreach ($tags as $key => $tag) {
                    $tag = trim($tag);

                    if ($key === 0) {
                        $trimmed_tags .= $upperLowerCase ? "'$tag'" : "'".mb_strtolower($tag)."'";
                    } else {
                        $trimmed_tags .= $upperLowerCase ? ", '$tag'" : ", '".mb_strtolower($tag)."'";
                    }
                }
            }
        }
        $queryTag = "pris.id in (
            SELECT pris_tag.pris_id from pris_tag
            LEFT JOIN tag on pris_tag.tag_id = tag.id
            LEFT JOIN tag_translations on tag.id = tag_translations.tag_id AND tag_translations.locale = '$locale'
            WHERE $where
        )";
        if ($logicalAnd == "AND" && isset($trimmed_tags,$tags_count)) {
            $whereLabel = $upperLowerCase ? "TRIM(tt.label)" : "LOWER(TRIM(tt.label))";
            $queryTag = "pris.id in (
                SELECT p.id FROM pris p
                JOIN pris_tag pt ON p.id = pt.pris_id
                JOIN tag t ON pt.tag_id = t.id
                JOIN tag_translations tt ON t.id = tt.tag_id
                WHERE $whereLabel IN ($trimmed_tags)
                GROUP BY p.id
                HAVING COUNT(DISTINCT tt.label) = $tags_count
            )";
        }

        $this->query->whereRaw($queryTag);;
    }
}

