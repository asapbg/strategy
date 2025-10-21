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
                $locale = app()->getLocale();
                $condition = $upperLowerCase ? 'LIKE' : 'ILIKE';

                $whereFulltext = $value;
                $whereTag = "tag_translations.label $condition '%$value%'";
                $whereAbout = "pris_translations.about $condition '%$value%'";
                $whereLegalReason = "pris_translations.legal_reason $condition '%$value%'";
                $whereImporter = "pris.old_importers $condition '%$value%'
                    OR exists (select * from pris_translations t where pris.id = t.pris_id and locale = '$locale' AND importer::text $condition '%$value%')
                ";
                if ($fullKeyword) {
                    $whereTag = "TRIM(tag_translations.label) $condition '$value'";

                    $whereAbout = "(";
                    $whereAbout .= "pris_translations.about $condition '% $value %'";
                    $whereAbout .= " OR pris_translations.about $condition '% $value'";
                    $whereAbout .= " OR pris_translations.about $condition '$value %'";
                    $whereAbout .= " OR pris_translations.about $condition '%$value,%'";
                    $whereAbout .= " OR pris_translations.about $condition '%$value/%'";
                    $whereAbout .= ")";

                    $whereLegalReason = "(";
                    $whereLegalReason .= "pris_translations.legal_reason $condition '% $value %'";
                    $whereLegalReason .= " OR pris_translations.legal_reason $condition '% $value'";
                    $whereLegalReason .= " OR pris_translations.legal_reason $condition '$value %'";
                    $whereLegalReason .= " OR pris_translations.legal_reason $condition '%$value,%'";
                    $whereLegalReason .= " OR pris_translations.legal_reason $condition '%$value/%'";
                    $whereLegalReason .= ")";

                    $whereImporter = "(";
                    $whereImporter .= "pris.old_importers $condition '% $value %'";
                    $whereImporter .= " OR pris.old_importers $condition '% $value'";
                    $whereImporter .= " OR pris.old_importers $condition '$value %'";
                    $whereImporter .= " OR pris.old_importers = '$value'";
                    if (!$upperLowerCase) {
                        $whereImporter .= " OR LOWER(pris.old_importers) = '$value'";
                    }
                    $whereImporter .= ")";
                    $whereImporter .= " OR exists (select * from pris_translations t where pris.id = t.pris_id and locale = '$locale' AND (";
                    $whereImporter .= "importer::text $condition '% $value %'";
                    $whereImporter .= " OR importer::text $condition '% $value'";
                    $whereImporter .= " OR importer::text $condition '$value %'";
                    $whereImporter .= " OR importer::text = '$value'";
                    $whereImporter .= "))";
                }
                //dd($whereImporter);
                $trimmed_tags = "";
                if (strstr($value, ",")) {
                    $tags = explode(",", $value);
                    $tags_count = count($tags);
                    $whereFulltext = "";
                    $whereAbout = "(";
                    $whereLegalReason = "(";
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
                                    $whereTag .= $fullKeyword ? "OR tag_translations.label = '$tag'" : "OR tag_translations.label ILIKE '%$tag%'";
                                } else {
                                    $whereTag .= $fullKeyword
                                        ? "OR LOWER(TRIM(tag_translations.label)) = '".mb_strtolower($tag)."'"
                                        : "OR LOWER(TRIM(tag_translations.label)) ILIKE '%".mb_strtolower($tag)."%'";
                                }
                                $whereAbout .= " OR ";
                                $whereLegalReason .= " OR ";
                            }
                            if ($fullKeyword) {
                                $whereAbout .= "(";
                                $whereAbout .= "pris_translations.about $condition '% $tag %'";
                                $whereAbout .= " OR pris_translations.about $condition '% $tag'";
                                $whereAbout .= " OR pris_translations.about $condition '$tag %'";
                                $whereAbout .= ")";

                                $whereLegalReason .= "(";
                                $whereLegalReason .= "pris_translations.legal_reason $condition '% $tag %'";
                                $whereLegalReason .= " OR pris_translations.legal_reason $condition '% $tag'";
                                $whereLegalReason .= " OR pris_translations.legal_reason $condition '$tag %'";
                                $whereLegalReason .= ")";
                            } else {
                                $whereAbout .= "pris_translations.about $condition '%$tag%'";
                                $whereLegalReason .= "pris_translations.legal_reason $condition '%$tag%'";
                            }
                        }
                        $whereTag .= " )";
                    } else {
                        foreach ($tags as $key => $tag) {
                            $tag = trim($tag);

                            if ($key === 0) {
                                $whereFulltext .= $tag;
                                $trimmed_tags .= $upperLowerCase ? "'$tag'" : "'".mb_strtolower($tag)."'";
                            } else {
                                $whereFulltext .= " & $tag";
                                $trimmed_tags .= $upperLowerCase ? ", '$tag'" : ", '".mb_strtolower($tag)."'";
                                $whereAbout .= " AND ";
                                $whereLegalReason .= " AND ";
                            }
                            if ($fullKeyword) {
                                $whereAbout .= "(";
                                $whereAbout .= "pris_translations.about $condition '% $tag %'";
                                $whereAbout .= " OR pris_translations.about $condition '% $tag'";
                                $whereAbout .= " OR pris_translations.about $condition '$tag %'";
                                $whereAbout .= ")";

                                $whereLegalReason .= "(";
                                $whereLegalReason .= "pris_translations.legal_reason $condition '% $tag %'";
                                $whereLegalReason .= " OR pris_translations.legal_reason $condition '% $tag'";
                                $whereLegalReason .= " OR pris_translations.legal_reason $condition '$tag %'";
                                $whereLegalReason .= ")";
                            } else {
                                $whereAbout .= "pris_translations.about $condition '%$tag%'";
                                $whereLegalReason .= "pris_translations.legal_reason $condition '%$tag%'";
                            }
                        }
                    }
                    $whereAbout .= ")";
                    $whereLegalReason .= ")";
                }
                //dd($whereAbout);
                $queryTag = "pris.id in (
                    SELECT pris_tag.pris_id from pris_tag
                    LEFT JOIN tag on pris_tag.tag_id = tag.id
                    LEFT JOIN tag_translations on tag.id = tag_translations.tag_id AND tag_translations.locale = '$locale'
                    WHERE $whereTag
                )";
                if ($logicalAnd == "AND" && isset($trimmed_tags,$tags_count)) {
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
                        SELECT p.id FROM pris p
                        JOIN pris_tag pt ON p.id = pt.pris_id
                        JOIN tag t ON pt.tag_id = t.id
                        JOIN tag_translations tt ON t.id = tt.tag_id
                        WHERE $whereClause
                        GROUP BY p.id
                        HAVING $having
                    )";
                }
                //dd($queryTag);$fullKeyword, $upperLowerCase
                if ($searchInFiles || $searchInAbout || $searchInLegalReason || $searchInTags || $searchInImporter) {
                    $q->where(function ($q) use (
                        $searchInFiles,
                        $searchInAbout,
                        $searchInLegalReason,
                        $searchInTags,
                        $searchInImporter,
                        $whereFulltext,
                        $queryTag,
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
                                        //$query->whereRaw("to_tsvector('bulgarian', file_text_ts_bg::text) @@ plainto_tsquery('bulgarian', ?)", [$whereFulltext]);
                                        $query->whereRaw("file_text_ts_bg @@ to_tsquery('simple', '$whereFulltext')");
//                                        $query->whereRaw("file_text::TEXT $condition '% $whereFulltext %'");
                                    } else {
                                        $query->whereRaw("file_text_ts_bg @@ to_tsquery('simple', '$whereFulltext:*')");
//                                        $query->whereRaw("file_text::TEXT $condition '%$whereFulltext%'");
                                    }
                                });
                            })
                            ->when($searchInAbout, function ($query) use ($whereAbout) {
                                $query->orWhereRaw($whereAbout);
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


