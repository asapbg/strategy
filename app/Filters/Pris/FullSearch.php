<?php

namespace App\Filters\Pris;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;


class FullSearch extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        $searchInFiles = is_array($filter) && isset($filter['fileSearch']) ? 1 : null;
        $searchInAbout = is_array($filter) && isset($filter['aboutSearch']) ? 1 : null;
        $searchInLegalReason = is_array($filter) && isset($filter['legalReasonSearch']) ? 1 : null;
        $searchInTags = is_array($filter) && isset($filter['tagsSearch']) ? 1 : null;
        if( !empty($value) ){
            $this->query->where(function ($q) use($searchInFiles,$searchInAbout,$searchInLegalReason,$searchInTags, $value){
                if($searchInFiles || $searchInAbout || $searchInLegalReason || $searchInTags){
                    $q->where(function ($q) use($searchInFiles,$searchInAbout,$searchInLegalReason,$searchInTags, $value){
                        $q->where('pris.id', '=', 0)
                            ->when($searchInFiles, function ($query) use($value){
                                $query->orWhereHas('files', function (Builder $query) use ($value){
                                    $query->whereRaw('file_text_ts_bg @@ plainto_tsquery(\'bulgarian\', ?)', [$value]);
                                });
                            })
                            ->when($searchInAbout, function ($query) use($value){
                                $query->orWhere('pris_translations.about', 'ilike', '%'.$value.'%');
                            })
                            ->when($searchInLegalReason, function ($query) use($value){
                                $query->orWhere('pris_translations.legal_reason', 'ilike', '%'.$value.'%');
                            })
                            ->when($searchInTags, function ($query) use($value){
                                $query->orWhereRaw('pris.id in ( select pris_tag.pris_id from pris_tag
                                    LEFT JOIN "tag" on "pris_tag"."tag_id" = "tag"."id"
                                    LEFT JOIN "tag_translations" on "tag_translations"."tag_id" = "tag"."id" and "tag_translations"."locale" = \''.app()->getLocale().'\'
                                    where tag_translations.label ilike \'%'.$value.'%\'
                                )');
                            });
                    });

                } else{
                    $q->where('pris_translations.about', 'ilike', '%'.$value.'%')
                        ->orWhere('pris_translations.legal_reason', 'ilike', '%'.$value.'%')
                        ->orWhereRaw('pris.id in ( select pris_tag.pris_id from pris_tag
                            LEFT JOIN "tag" on "pris_tag"."tag_id" = "tag"."id"
                            LEFT JOIN "tag_translations" on "tag_translations"."tag_id" = "tag"."id" and "tag_translations"."locale" = \''.app()->getLocale().'\'
                            where tag_translations.label ilike \'%'.$value.'%\'
                        )')
                        ->orWhereHas('files', function (Builder $query) use ($value){
                            $query->whereRaw('file_text_ts_bg @@ plainto_tsquery(\'bulgarian\', ?)', [$value]);
                        });
                }

            });
        };
    }
}


