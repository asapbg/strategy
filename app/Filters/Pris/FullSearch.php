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
                $logicalАnd = is_array($filter) && isset($filter['logicalАnd']) ? "AND" : "OR";
                $searchInFiles = is_array($filter) && isset($filter['fileSearch']) ? 1 : null;
                $searchInAbout = is_array($filter) && isset($filter['aboutSearch']) ? 1 : null;
                $searchInLegalReason = is_array($filter) && isset($filter['legalReasonSearch']) ? 1 : null;
                $searchInTags = is_array($filter) && isset($filter['tagsSearch']) ? 1 : null;

                $whereTag = "tag_translations.label ilike '%$value%'";
                $whereAbout = "pris_translations.about ilike '%$value%'";
                $whereLegalReason = "pris_translations.legal_reason ilike '%$value%'";
                $trimmed_tags = "";
                if (strstr($value, ",")) {
                    $tags = explode(",", $value);
                    $tags_count = count($tags);
                    $whereAbout = "(";
                    $whereLegalReason = "(";
                    if ($logicalАnd == "OR") {
                        $whereTag = "(";
                        foreach ($tags as $key => $tag) {
                            $tag = trim($tag);
                            $whereTag .= $key === 0 ? "tag_translations.label = '$tag'" : " OR tag_translations.label = '$tag'";
                            $whereAbout .= $key === 0 ? "pris_translations.about ilike '%$tag%'" : " OR pris_translations.about ilike '%$tag%'";
                            $whereLegalReason .= $key === 0 ? "pris_translations.legal_reason ilike '%$tag%'" : " OR pris_translations.legal_reason ilike '%$tag%'";
                        }
                        $whereTag .= " )";
                    } else {
                        foreach ($tags as $key => $tag) {
                            $tag = trim(mb_strtolower($tag));
                            $trimmed_tags .= $key === 0 ? "'$tag'" : ", '$tag'";
                            $whereAbout .= $key === 0 ? "pris_translations.about ilike '%$tag%'" : " AND pris_translations.about ilike '%$tag%'";
                            $whereLegalReason .= $key === 0 ? "pris_translations.legal_reason ilike '%$tag%'" : " AND pris_translations.legal_reason ilike '%$tag%'";
                        }
                    }
                    $whereAbout .= ")";
                    $whereLegalReason .= ")";
                }
                //dd($whereTag);
                $locale = app()->getLocale();
                $queryTag = "pris.id in (
                    SELECT pris_tag.pris_id from pris_tag
                    LEFT JOIN tag on pris_tag.tag_id = tag.id
                    LEFT JOIN tag_translations on tag.id = tag_translations.tag_id AND tag_translations.locale = '$locale'
                    WHERE $whereTag
                )";
                if ($logicalАnd == "AND" && isset($trimmed_tags,$tags_count)) {
                    $queryTag = "pris.id in (
                        SELECT p.id FROM pris p
                        JOIN pris_tag pt ON p.id = pt.pris_id
                        JOIN tag t ON pt.tag_id = t.id
                        JOIN tag_translations tt ON t.id = tt.tag_id
                        WHERE tt.label IN ($trimmed_tags)
                        GROUP BY p.id
                        HAVING COUNT(DISTINCT tt.label) = $tags_count
                    )";
                }
                //dd($queryTag);
                if ($searchInFiles || $searchInAbout || $searchInLegalReason || $searchInTags) {
                    $q->where(function ($q) use ($searchInFiles, $searchInAbout, $searchInLegalReason, $searchInTags, $value, $queryTag, $whereAbout, $whereLegalReason) {
                        $q->where('pris.id', '=', 0)
                            ->when($searchInFiles, function ($query) use ($value) {
                                $query->orWhereHas('files', function (Builder $query) use ($value) {
                                    $query->whereRaw('file_text_ts_bg @@ plainto_tsquery(\'bulgarian\', ?)', [$value]);
                                });
                            })
                            ->when($searchInAbout, function ($query) use ($whereAbout) {
                                $query->orWhereRaw($whereAbout);
                            })
                            ->when($searchInLegalReason, function ($query) use ($whereLegalReason) {
                                $query->orWhereRaw($whereLegalReason);
                            })
                            ->when($searchInTags, function ($query) use ($queryTag) {
                                $query->orWhereRaw($queryTag);
                            });
                    });

                } else {
                    $q->where('pris_translations.about', 'ilike', '%' . $value . '%')
                        ->orWhere('pris_translations.legal_reason', 'ilike', '%' . $value . '%')
                        ->orWhereRaw($queryTag)
                        ->orWhereHas('files', function (Builder $query) use ($value) {
                            $query->whereRaw('file_text_ts_bg @@ plainto_tsquery(\'bulgarian\', ?)', [$value]);
                        });
                }

            });
        }
    }
}


