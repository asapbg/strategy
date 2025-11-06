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
            $where = "TRIM(tag_translations.label) $condition '$value'";
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
                        if ($upperLowerCase) {
                            $where .= $fullKeyword ? "tag_translations.label = '$tag'" : "tag_translations.label ILIKE '%$tag%'";
                        } else {
                            $where .= $fullKeyword
                                ? "LOWER(TRIM(tag_translations.label)) = '" . mb_strtolower($tag) . "'"
                                : "LOWER(TRIM(tag_translations.label)) ILIKE '%" . mb_strtolower($tag) . "%'";
                        }
                    } else {
                        if ($upperLowerCase) {
                            $where .= $fullKeyword ? " OR tag_translations.label = '$tag'" : "OR tag_translations.label ILIKE '%$tag%'";
                        } else {
                            $where .= $fullKeyword
                                ? " OR LOWER(TRIM(tag_translations.label)) = '" . mb_strtolower($tag) . "'"
                                : " OR LOWER(TRIM(tag_translations.label)) ILIKE '%" . mb_strtolower($tag) . "%'";
                        }
                    }
                }
                $where .= " )";
            } else {
                foreach ($tags as $key => $tag) {
                    $tag = trim($tag);

                    if ($key === 0) {
                        $trimmed_tags .= $upperLowerCase ? "'$tag'" : "'" . mb_strtolower($tag) . "'";
                    } else {
                        $trimmed_tags .= $upperLowerCase ? ", '$tag'" : ", '" . mb_strtolower($tag) . "'";
                    }
                }
            }
        }
        $queryTag = "pris.id in (
            SELECT pris_tag.pris_id
              FROM pris_tag
         LEFT JOIN tag_translations on pris_tag.tag_id  = tag_translations.tag_id AND tag_translations.locale = '$locale'
             WHERE $where
        )";
        if ($logicalAnd == "AND" && !empty($trimmed_tags) && isset($tags_count)) {
            $whereLabel = $upperLowerCase ? "TRIM(tt.label)" : "LOWER(TRIM(tt.label))";
            if ($fullKeyword) {
                $whereClause = "$whereLabel IN ($trimmed_tags)";
                $having = "COUNT(DISTINCT tt.label) = $tags_count";
            } else {
                $patterns = array_map(function ($tag) use ($whereLabel) {
                    $tag = trim($tag);
                    if ($tag === '') {
                        return null; // игнориране на празни стойности
                    }
                    // preg_quote защитава специалните characters за regex
                    $escapedTag = preg_quote($tag, '/');
                    return "($whereLabel ILIKE '%$tag%')";
                }, $tags);
                $patterns = array_filter($patterns);
                $whereClause = implode(' OR ', $patterns);
                $patterns = array_map(function ($tag) {
                    $tag = trim($tag);
                    if ($tag === '') {
                        return null; // игнориране на празни стойности
                    }
                    return "COUNT(DISTINCT CASE WHEN LOWER(TRIM(tt.label)) ILIKE '%$tag%' THEN tt.label END) > 0";
                }, $tags);
                $patterns = array_filter($patterns);
                $having = implode(' AND ', $patterns);
            }
            //dd($whereClause);
            $queryTag = "pris.id in (
                SELECT p.id
                  FROM pris p
                  JOIN pris_tag pt ON p.id = pt.pris_id
                  JOIN tag t ON pt.tag_id = t.id
                  JOIN tag_translations tt ON t.id = tt.tag_id
                 WHERE $whereClause
              GROUP BY p.id
                HAVING $having
            )";
        }

        $this->query->whereRaw($queryTag);
    }
}

