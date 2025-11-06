<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Changes extends QueryFilter implements FilterContract
{

    public function handle($value, $filter = null): void
    {
        $logicalAnd = is_array($filter) && isset($filter['logicalАnd']) ? "AND" : "OR";
        $upperLowerCase = is_array($filter) && isset($filter['upperLowerCase']) ? true : null;
        $fullKeyword = is_array($filter) && isset($filter['fullKeyword']) ? true : null;
        $condition = $upperLowerCase ? 'LIKE' : 'ILIKE';

        $where = "full_text $condition '%$value%'";
        if ($fullKeyword) {
            $where = $this->buildWhereChanges($condition, $value);
        }
        if (str_contains($value, ",")) {
            $tags = explode(",", $value);
            $where = "(";
            foreach ($tags as $key => $tag) {
                $tag = trim($tag);
                $where .= ($key === 0) ? " (" : " $logicalAnd (";
                if ($fullKeyword) {
                    $where = $this->buildWhereChanges($condition, $tag, $where).")";
                } else {
                    $where .= "full_text $condition '%$tag%')";
                }
            }
            $where .= " )";

        }
        $queryChanges = "pris.id in (
            SELECT pris_id
              FROM pris_change_pris
             WHERE $where
        ) OR pris.id in (
            SELECT changed_pris_id
              FROM pris_change_pris
             WHERE $where
        )";
//        if ($logicalAnd == "AND" && !empty($trimmed_tags) && isset($tags_count)) {
//            $whereLabel = $upperLowerCase ? "TRIM(full_text)" : "LOWER(TRIM(full_text))";
//            if ($fullKeyword) {
//                $whereClause = "$whereLabel IN ($trimmed_tags)";
//                $having = "COUNT(DISTINCT full_text) = $tags_count";
//            } else {
//                $patterns = array_map(function ($tag) use ($whereLabel) {
//                    $tag = trim($tag);
//                    if ($tag === '') {
//                        return null; // игнориране на празни стойности
//                    }
//                    // preg_quote защитава специалните characters за regex
//                    $escapedTag = preg_quote($tag, '/');
//                    return "($whereLabel ILIKE '%$tag%')";
//                }, $tags);
//                $patterns = array_filter($patterns);
//                $whereClause = implode(' OR ', $patterns);
//                $patterns = array_map(function ($tag) {
//                    $tag = trim($tag);
//                    if ($tag === '') {
//                        return null; // игнориране на празни стойности
//                    }
//                    return "COUNT(DISTINCT CASE WHEN LOWER(TRIM(full_text)) ILIKE '%$tag%' THEN full_text END) > 0";
//                }, $tags);
//                $patterns = array_filter($patterns);
//                $having = implode(' AND ', $patterns);
//            }
//            //dd($whereClause);
//            $queryChanges = "pris.id in (
//                SELECT pris_id
//                  FROM pris_change_pris
//                 WHERE $whereClause
//              GROUP BY pris_id
//                HAVING $having
//            ) OR pris.id in (
//                SELECT changed_pris_id
//                  FROM pris_change_pris
//                 WHERE $whereClause
//              GROUP BY changed_pris_id
//                HAVING $having
//            )";
//        }
        $this->query->whereRaw($queryChanges);
    }
}

