<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;


class FullSearch extends QueryFilter implements FilterContract
{

    public function handle($value, $filter = null): void
    {
        if (!empty($value)) {
            $this->query->where(function ($q) use ($filter, $value) {
                //dump($value);
                $logicalAnd = is_array($filter) && isset($filter['logicalАnd']) ? "AND" : "OR";
                $fullKeyword = is_array($filter) && isset($filter['fullKeyword']) ? 1 : null;
                $upperLowerCase = is_array($filter) && isset($filter['upperLowerCase']) ? 1 : null;
                $searchInImporter = is_array($filter) && isset($filter['importer']) ? 1 : null;
                $searchInFiles = is_array($filter) && isset($filter['fileSearch']) ? 1 : null;
                $searchInAbout = is_array($filter) && isset($filter['aboutSearch']) ? 1 : null;
                $searchInLegalReason = is_array($filter) && isset($filter['legalReasonSearch']) ? 1 : null;
                $searchInTags = is_array($filter) && isset($filter['tagsSearch']) ? 1 : null;
                $searchInChanges = is_array($filter) && isset($filter['changesSearch']) ? 1 : null;
                $locale = app()->getLocale();
                $condition = $upperLowerCase ? 'LIKE' : 'ILIKE';

                $whereFulltext = $value;
                $whereTag = "tag_translations.label $condition '%$value%'";
                $whereAbout = "pris_translations.about $condition '%$value%'";
                $whereChanges = "full_text $condition '%$value%'";
                $whereLegalReason = "pris_translations.legal_reason $condition '%$value%'";
                $whereImporter = "pris.old_importers $condition '%$value%'
                    OR exists (select * from pris_translations t where pris.id = t.pris_id and locale = '$locale' AND importer::text $condition '%$value%')
                ";
                if ($fullKeyword) {
                    $whereTag = "TRIM(tag_translations.label) $condition '$value'";

                    $whereAbout = $this->buildWhereAbout($condition, $value);
                    $whereLegalReason = $this->buildWhereLegalReason($condition, $value);
                    $whereChanges = $this->buildWhereChanges($condition, $value);

                    $whereImporter = "(";
                    $whereImporter .= "pris.old_importers $condition ',% $value'";
                    $whereImporter .= " OR pris.old_importers $condition '$value,%'";
                    $whereImporter .= " OR pris.old_importers = '$value'";
                    if (!$upperLowerCase) {
                        $whereImporter .= " OR LOWER(pris.old_importers) = '$value'";
                    }
                    $whereImporter .= ")";
                    $whereImporter .= " OR exists (select * from pris_translations t where pris.id = t.pris_id and locale = '$locale' AND (";
                    $whereImporter .= "importer::text $condition ',% $value'";
                    $whereImporter .= " OR importer::text $condition '$value,%'";
                    $whereImporter .= " OR importer::text = '$value'";
                    $whereImporter .= "))";
                }
                //dd($whereImporter);
                $trimmed_tags = "";
                if (str_contains($value, ",")) {
                    $tags = explode(",", $value);
                    $tags_count = count($tags);
                    $whereFulltext = "";
                    $whereAbout = "(";
                    $whereLegalReason = "(";
                    $whereChanges = "(";
                    if ($logicalAnd == "OR") {
                        $whereTag = "(";
                        foreach ($tags as $key => $tag) {
                            $tag = trim($tag);
                            if ($key === 0) {
                                $whereFulltext .= $tag;
                                if ($upperLowerCase) {
                                    $whereTag .= $fullKeyword ? "tag_translations.label = '$tag'" : "tag_translations.label ILIKE '%$tag%'";
                                } else {
                                    $whereTag .= $fullKeyword
                                        ? "LOWER(TRIM(tag_translations.label)) = '".mb_strtolower($tag)."'"
                                        : "LOWER(TRIM(tag_translations.label)) ILIKE '%".mb_strtolower($tag)."%'";
                                }
                            } else {
                                $whereFulltext .= " | $tag";
                                if ($upperLowerCase) {
                                    $whereTag .= $fullKeyword ? " OR tag_translations.label = '$tag'" : "OR tag_translations.label ILIKE '%$tag%'";
                                } else {
                                    $whereTag .= $fullKeyword
                                        ? " OR LOWER(TRIM(tag_translations.label)) = '".mb_strtolower($tag)."'"
                                        : " OR LOWER(TRIM(tag_translations.label)) ILIKE '%".mb_strtolower($tag)."%'";
                                }
                                $whereAbout .= " OR ";
                                $whereLegalReason .= " OR ";
                                $whereChanges .= " OR ";
                            }
                            if ($fullKeyword) {
                                $whereAbout = $this->buildWhereAbout($condition, $tag, $whereAbout);
                                $whereLegalReason = $this->buildWhereLegalReason($condition, $tag, $whereLegalReason);
                                $whereChanges = $this->buildWhereChanges($condition, $tag, $whereChanges);
                            } else {
                                $whereAbout .= "pris_translations.about $condition '%$tag%'";
                                $whereLegalReason .= "pris_translations.legal_reason $condition '%$tag%'";
                                $whereChanges .= "full_text $condition '%$tag%'";
                            }
                        }
                        $whereTag .= " )";
                    } else {
                        foreach ($tags as $key => $tag) {
                            $tag = trim($tag);

                            if ($key === 0) {
                                $whereAbout .= "(";
                                $whereLegalReason .= "(";
                                $whereChanges .= "(";
                                $whereFulltext .= $tag;
                                $trimmed_tags .= $upperLowerCase ? "'$tag'" : "'".mb_strtolower($tag)."'";
                            } else {
                                $whereFulltext .= " & $tag";
                                $trimmed_tags .= $upperLowerCase ? ", '$tag'" : ", '".mb_strtolower($tag)."'";
                                $whereAbout .= " AND (";
                                $whereLegalReason .= " AND (";
                                $whereChanges .= " AND (";
                            }
                            if ($fullKeyword) {
                                $whereAbout = $this->buildWhereAbout($condition, $tag, $whereAbout).")";
                                $whereLegalReason = $this->buildWhereLegalReason($condition, $tag, $whereLegalReason).")";
                                $whereChanges = $this->buildWhereChanges($condition, $tag, $whereChanges).")";
                            } else {
                                $whereAbout .= "pris_translations.about $condition '%$tag%'".")";
                                $whereLegalReason .= "pris_translations.legal_reason $condition '%$tag%'".")";
                                $whereChanges .= "full_text $condition '%$tag%'".")";
                            }
                        }
                    }
                    $whereAbout .= ")";
                    $whereLegalReason .= ")";
                    $whereChanges .= " )";
                }
                //dd($whereChanges);
                $queryTag = "pris.id in (
                    SELECT pris_tag.pris_id
                      FROM pris_tag
                 LEFT JOIN tag_translations on pris_tag.tag_id  = tag_translations.tag_id AND tag_translations.locale = '$locale'
                     WHERE $whereTag
                )";
                $queryChanges = "pris.id in (
                    SELECT pris_id
                      FROM pris_change_pris
                     WHERE $whereChanges
                ) OR pris.id in (
                    SELECT changed_pris_id
                      FROM pris_change_pris
                     WHERE $whereChanges
                )";
                if ($logicalAnd == "AND" && !empty($trimmed_tags) && isset($tags_count) && $searchInTags) {
                    $whereLabel = $upperLowerCase ? "TRIM(tt.label)" : "LOWER(TRIM(tt.label))";
                    if ($fullKeyword) {
                        $whereClause = "$whereLabel IN ($trimmed_tags)";
                        $having = "COUNT(DISTINCT tt.label) = $tags_count";
                    } else {
                        $patterns = array_map(function($tag) use ($whereLabel) {
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
                        $patterns = array_map(function($tag) {
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
//                if ($logicalAnd == "AND" && !empty($trimmed_tags) && isset($tags_count) && $searchInChanges) {
//                    $whereText = $upperLowerCase ? "TRIM(full_text)" : "LOWER(TRIM(full_text))";
//                    if ($fullKeyword) {
//                        $whereClause = "$whereText IN ($trimmed_tags)";
//                        $having = "COUNT(DISTINCT full_text) = $tags_count";
//                    } else {
//                        $patterns = array_map(function($tag) use ($whereText) {
//                            $tag = trim($tag);
//                            if ($tag === '') {
//                                return null;
//                            }
//                            return "($whereText ILIKE '%$tag%')";
//                        }, $tags);
//                        $patterns = array_filter($patterns);
//                        $whereClause = implode(' OR ', $patterns);
//                        $patterns = array_map(function($tag) {
//                            $tag = trim($tag);
//                            if ($tag === '') {
//                                return null; // игнориране на празни стойности
//                            }
//                            return "COUNT(DISTINCT CASE WHEN LOWER(TRIM(full_text)) ILIKE '%$tag%' THEN full_text END) > 0";
//                        }, $tags);
//                        $patterns = array_filter($patterns);
//                        $having = implode(' AND ', $patterns);
//                    }
//                    //dd($whereClause);
//                    $queryChanges = "pris.id in (
//                        SELECT pris_id
//                          FROM pris_change_pris
//                         WHERE $whereClause
//                      GROUP BY pris_id
//                        HAVING $having
//                    ) OR pris.id in (
//                        SELECT changed_pris_id
//                          FROM pris_change_pris
//                         WHERE $whereClause
//                      GROUP BY changed_pris_id
//                        HAVING $having
//                    )";
//                }
                //dd($queryChanges);
                //dd($queryTag);$fullKeyword, $upperLowerCase
                if ($searchInFiles || $searchInAbout || $searchInChanges || $searchInLegalReason || $searchInTags || $searchInImporter) {
                    $q->where(function ($q) use (
                        $searchInFiles,
                        $searchInAbout,
                        $searchInChanges,
                        $searchInLegalReason,
                        $searchInTags,
                        $searchInImporter,
                        $whereFulltext,
                        $queryTag,
                        $queryChanges,
                        $whereAbout,
                        $whereImporter,
                        $fullKeyword,
                        $upperLowerCase,
                        $whereLegalReason
                    ) {
                        $q->where('pris.id', '=', 0)
                            ->when($searchInFiles, function ($query) use ($whereFulltext, $fullKeyword, $upperLowerCase) {
                                $query->orWhereHas('files', function (Builder $query) use ($whereFulltext, $fullKeyword, $upperLowerCase) {
                                    $condition = $upperLowerCase ? 'LIKE' : 'ILIKE';
                                    if ($fullKeyword) {
                                        $query->whereRaw("file_text_ts_bg @@ to_tsquery('simple', '$whereFulltext')");
                                        //$query->whereRaw("to_tsvector('bulgarian', file_text_ts_bg::text) @@ plainto_tsquery('bulgarian', ?)", [$whereFulltext]);
                                        //$query->whereRaw("file_text::TEXT $condition '% $whereFulltext %'");
                                    } else {
                                        $query->whereRaw("file_text_ts_bg @@ to_tsquery('simple', '$whereFulltext:*')");
                                        //$query->whereRaw("file_text::TEXT $condition '%$whereFulltext%'");
                                    }
                                });
                            })
                            ->when($searchInAbout, function ($query) use ($whereAbout) {
                                $query->orWhereRaw($whereAbout);
                            })
                            ->when($searchInChanges, function ($query) use ($queryChanges) {
                                $query->orWhereRaw($queryChanges);
                            })
                            ->when($searchInLegalReason, function ($query) use ($whereLegalReason) {
                                $query->orWhereRaw($whereLegalReason);
                            })
                            ->when($searchInImporter, function ($query) use ($whereImporter) {
                                $query->orWhereRaw($whereImporter);
                            })
                            ->when($searchInTags, function ($query) use ($queryTag) {
                                $query->orWhereRaw($queryTag);
                            });
                    });

                } else {
                    $q->where('pris_translations.about', "$condition" , "%'$value'%")
                        ->orWhere('pris_translations.legal_reason', "$condition", "%'$value'%")
                        ->orWhereRaw($queryTag)
                        ->orWhereHas('files', function (Builder $query) use ($value) {
                            $query->whereRaw('file_text_ts_bg @@ plainto_tsquery(\'bulgarian\', ?)', [$value]);
                        });
                }

            });
        }
    }
}


