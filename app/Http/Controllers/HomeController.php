<?php

namespace App\Http\Controllers;

use App\Enums\PublicationTypesEnum;
use App\Models\Consultations\PublicConsultation;
use App\Models\File;
use App\Models\LegalActType;
use App\Models\LegislativeInitiative;
use App\Models\Publication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Enums\LegislativeInitiativeStatusesEnum;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return View
     */
    public function index()
    {
        $consultations = $this->getConsultations(new Request());
        $initiatives = $this->getInitiatives(new Request());

        $publications = Publication::select('publication.*')
            ->whereActive(true)
            ->with(['translation','mainImg','category.translation'])
            ->joinTranslation(Publication::class)
            ->whereLocale(currentLocale())
            //->whereType(PublicationTypesEnum::TYPE_NEWS)
            ->whereDate('created_at', '<=', date('Y-m-d'))
            ->orderBy('created_at', 'DESC')
            ->paginate(3);

        $default_img = "files".DIRECTORY_SEPARATOR.File::PUBLICATION_UPLOAD_DIR."news-default.jpg";

        //dd($publications->toArray());
        return $this->view('site.home.index',
            compact('consultations','initiatives', 'publications','default_img'));
    }

    /**
     * @params Request $request
     * @return mixed
     */
    public function getConsultations(Request $request)
    {
        $paginate = 4;
        $is_search = $request->has('search');
        $title = $request->offsetGet('pc_search_title');
        $consultations = PublicConsultation::select('public_consultation.*')
            ->ActivePublic()
            ->where('public_consultation_translations.locale', app()->getLocale())
            ->with(['translation', 'fieldOfAction.translation'])
            ->with('comments:object_id')
            ->jointranslation(PublicConsultation::class)
            ->join('field_of_actions', 'field_of_actions.id', '=', 'public_consultation.field_of_actions_id')
            ->join('field_of_action_translations', function ($q) {
                $q->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->when($title, function ($query, $title) {
                return $query->where('field_of_action_translations.name', 'ILIKE', "%$title%")
                    ->orWhere('public_consultation_translation.title', 'ILIKE', "%$title%");
            })
            ->orderBy('public_consultation.created_at', 'DESC')
            ->paginate($paginate);

        if ($is_search) {
            return $this->view('site.home.consultations', compact('consultations'));
        }

        return $consultations;
    }

    /**
     * @params Request $request
     * @return mixed
     */
    public function getInitiatives(Request $request)
    {
        $paginate = 4;
        $is_search = $request->has('search');
        $keywords = $request->offsetGet('keywords');
        $initiatives = LegislativeInitiative::select('legislative_initiative.*')->with(['comments','likes'])
            ->join('law', 'law.id', '=', 'legislative_initiative.law_id')
            ->join('law_translations', function ($q){
                $q->on('law_translations.law_id', '=', 'law.id')->where('law_translations.locale', '=', app()->getLocale());
            })
            ->when(!empty($keywords), function ($query) use ($keywords){
                $query->where('law_translations.name', 'ilike', '%' . $keywords . '%');
                $query->orWhere('legislative_initiative.description', 'ilike', '%' . $keywords . '%')
                    ->orWhereHas('user', function ($query) use ($keywords) {
                        $query->where('first_name', 'like', '%' . $keywords . '%');
                        $query->orWhere('middle_name', 'like', '%' . $keywords . '%');
                        $query->orWhere('last_name', 'like', '%' . $keywords . '%');
                    });
            })
            ->whereNull('law.deleted_at')
            ->where('law.active', '=', true)
//            ->whereStatus(LegislativeInitiativeStatusesEnum::STATUS_ACTIVE)
            ->orderBy('legislative_initiative.created_at', 'DESC')
            ->groupBy('legislative_initiative.id')
            ->paginate($paginate);

        if ($is_search) {
            return $this->view('site.home.initiatives', compact('initiatives'));
        }

        return $initiatives;
    }

    public function search(Request $request)
    {
        $defaultPaginate = config('app.default_paginate');
        $totalResults = 0;
        $items = array();
        $perPage = $defaultPaginate;
        $page = $request->input('page', 1);
        $offset = ($page * $perPage) - $perPage;
        $search = $request->input('search') ?? '';
        $search = mb_strtolower($search);
        $nowDate = Carbon::now()->format('Y-m-d');
        $nowDateTimeStamp = Carbon::now()->format('Y-m-d H:i:s');
        $locale = app()->getLocale();

        //Count total
        $totalCnt = \DB::select('
            select
                sum(result.cnt)
            from
                (
                    select
                        count(public_consultation.id) as cnt
                    from public_consultation
                    join public_consultation_translations on public_consultation_translations.public_consultation_id = public_consultation.id and public_consultation_translations.locale = \'' . $locale . '\'
                    where true
                        and public_consultation.deleted_at is null
                        and public_consultation.active = 1
                        and (
                            public_consultation_translations.title ilike \'%' . $search . '%\'
                            or public_consultation_translations.description ilike \'%' . $search . '%\'
                        )
                    and public_consultation.open_from <= \'' . $nowDate . '\'
                    union all
                        select
                        count(advisory_boards.id) as cnt
                    from advisory_boards
                    join advisory_board_translations on advisory_board_translations.advisory_board_id = advisory_boards.id and advisory_board_translations.locale = \'' . $locale . '\'
                    where true
                        and advisory_boards.deleted_at is null
                        and advisory_boards.public = true
                        and (
                            advisory_board_translations.name ilike \'%' . $search . '%\'
                        )
                    union all
                        select
                            count(strategic_document.id) as cnt
                        from strategic_document
                        join strategic_document_translations on strategic_document_translations.strategic_document_id = strategic_document.id and strategic_document_translations.locale = \'' . $locale . '\'
                        where true
                            and strategic_document.deleted_at is null
                            and strategic_document.active = true
                            and (
                                strategic_document_translations.title ilike \'%' . $search . '%\'
                            )
                    union all
                        select
                            count(legislative_initiative.id) as cnt
                        from legislative_initiative
                        join law on law.id = legislative_initiative.law_id
                        join law_translations on law_translations.law_id = law.id and law_translations.locale = \'' . $locale . '\'
                        where true
                            and legislative_initiative.deleted_at is null
                            and (
                                legislative_initiative.description ilike \'%' . $search . '%\'
                                or legislative_initiative.law_paragraph ilike \'%' . $search . '%\'
                                or legislative_initiative.law_text ilike \'%' . $search . '%\'
                            )
                    union all
                        select
                            count(pris.id) as cnt
                        from pris
                        join pris_translations on pris_translations.pris_id = pris.id and pris_translations.locale = \'' . $locale . '\'
                        join legal_act_type on pris.legal_act_type_id = legal_act_type.id
                        join legal_act_type_translations on legal_act_type_translations.legal_act_type_id = legal_act_type.id and legal_act_type_translations.locale = \'' . $locale . '\'
                        where true
                            and pris.deleted_at is null
                            and pris.active = 1
                            and pris.published_at  is not null
                            and pris.legal_act_type_id <> ' . LegalActType::TYPE_ARCHIVE . '
                            and (
                                pris_translations.about ilike \'%' . $search . '%\'
                                or pris_translations.legal_reason ilike \'%' . $search . '%\'
                                or pris_translations.importer ilike \'%' . $search . '%\'
                            )
                    union all
                        select
                            count(publication.id) as cnt
                        from publication
                        join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \'' . $locale . '\'
                        where true
                            and publication.deleted_at is null
                            and publication.active = true
                            and publication.published_at >= \'' . $nowDate . '\'
                            and publication.type >= ' . PublicationTypesEnum::TYPE_LIBRARY->value . '
                            and (
                                publication_translations.title ilike \'%' . $search . '%\'
                                or publication_translations.content ilike \'%' . $search . '%\'
                                or publication_translations.short_content ilike \'%' . $search . '%\'
                            )
                    union all
                        select
                            count(publication.id) as cnt
                        from publication
                        join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \'' . $locale . '\'
                        where true
                            and publication.deleted_at is null
                            and publication.active = true
                            and publication.published_at >= \'' . $nowDate . '\'
                            and publication.type >= ' . PublicationTypesEnum::TYPE_NEWS->value . '
                            and (
                                publication_translations.title ilike \'%' . $search . '%\'
                                or publication_translations.content ilike \'%' . $search . '%\'
                                or publication_translations.short_content ilike \'%' . $search . '%\'
                            )
                    union all
                        select
                            count(publication.id) as cnt
                        from publication
                        join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \'' . $locale . '\'
                        where true
                            and publication.deleted_at is null
                            and publication.active = true
                            and publication.published_at >= \'' . $nowDate . '\'
                            and publication.type >= ' . PublicationTypesEnum::TYPE_OGP_NEWS->value . '
                            and (
                                publication_translations.title ilike \'%' . $search . '%\'
                                or publication_translations.content ilike \'%' . $search . '%\'
                                or publication_translations.short_content ilike \'%' . $search . '%\'
                            )
                ) as result
        ');

        if(isset($totalCnt[0]) && $totalCnt[0]->sum) {
            $totalResults = $totalCnt[0]->sum;
            //Search in Adv Boards
            $advItemsUnion = '
                select
                    advisory_boards.id,
                    advisory_board_translations.name,
                    \'adv_board\' as item_type,
                    \'\' as act_type_name
                from advisory_boards
                join advisory_board_translations on advisory_board_translations.advisory_board_id = advisory_boards.id and advisory_board_translations.locale = \'' . $locale . '\'
                where true
                    and advisory_boards.deleted_at is null
                    and advisory_boards.public = true
                    and (
                        advisory_board_translations.name ilike \'%' . $search . '%\'
                    )
            ';

            //Search in Strategic documents
            $sdItemsUnion = '
                select
                    strategic_document.id,
                    strategic_document_translations.title as name,
                    \'sd\' as item_type,
                    \'\' as act_type_name
                from strategic_document
                join strategic_document_translations on strategic_document_translations.strategic_document_id = strategic_document.id and strategic_document_translations.locale = \'' . $locale . '\'
                where true
                    and strategic_document.deleted_at is null
                    and strategic_document.active = true
                    and (
                        strategic_document_translations.title ilike \'%' . $search . '%\'
                    )
            ';

            //Search in legislative Initiative
            $liItemsUnion = '
                select
                    legislative_initiative.id,
                    \''.__('custom.change_f').' '.__('custom.in').'\' || \' \' || law_translations.name,
                    \'li\' as item_type,
                    \'\' as act_type_name
                from legislative_initiative
                join law on law.id = legislative_initiative.law_id
                join law_translations on law_translations.law_id = law.id and law_translations.locale = \'' . $locale . '\'
                where true
                    and legislative_initiative.deleted_at is null
                    and (
                        legislative_initiative.description ilike \'%' . $search . '%\'
                        or legislative_initiative.law_paragraph ilike \'%' . $search . '%\'
                        or legislative_initiative.law_text ilike \'%' . $search . '%\'
                    )
            ';

            //Search in PRIS
            $prisItemsUnion = '
                select
                    pris.id,
                    legal_act_type_translations.name || \' \' || \'' . __('custom.number_symbol') . '\' || pris.doc_num || \' \' || \'' . __('custom.of') . '\' || \''.__('site.the_ministry').'\' || \' \' || \''.__('custom.from').'\' || \' \' || date_part(\'year\',pris.doc_date) || \''.__('custom.year_short').'\' as name,
                    \'pris\' as item_type,
                    legal_act_type_translations.name as act_type_name
                from pris
                join pris_translations on pris_translations.pris_id = pris.id and pris_translations.locale = \'' . $locale . '\'
                join legal_act_type on pris.legal_act_type_id = legal_act_type.id
                join legal_act_type_translations on legal_act_type_translations.legal_act_type_id = legal_act_type.id and legal_act_type_translations.locale = \'' . $locale . '\'
                where true
                    and pris.deleted_at is null
                    and pris.active = 1
                    and pris.published_at  is not null
                    and pris.legal_act_type_id <> ' . LegalActType::TYPE_ARCHIVE . '
                    and (
                        pris_translations.about ilike \'%' . $search . '%\'
                        or pris_translations.legal_reason ilike \'%' . $search . '%\'
                        or pris_translations.importer ilike \'%' . $search . '%\'
                    )
            ';

            //Search in Publications
            $publicationsItemsUnion = '
                select
                    publication.id,
                    publication_translations.title as name,
                    \'publications\' as item_type,
                    \'\' as act_type_name
                from publication
                join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \'' . $locale . '\'
                where true
                    and publication.deleted_at is null
                    and publication.active = true
                    and publication.published_at >= \'' . $nowDate . '\'
                    and publication.type >= ' . PublicationTypesEnum::TYPE_LIBRARY->value . '
                    and (
                        publication_translations.title ilike \'%' . $search . '%\'
                        or publication_translations.content ilike \'%' . $search . '%\'
                        or publication_translations.short_content ilike \'%' . $search . '%\'
                    )
            ';

            //Search in News
            $newsItemsUnion = '
                select
                    publication.id,
                    publication_translations.title as name,
                    \'news\' as item_type,
                    \'\' as act_type_name
                from publication
                join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \'' . $locale . '\'
                where true
                    and publication.deleted_at is null
                    and publication.active = true
                    and publication.published_at >= \'' . $nowDate . '\'
                    and publication.type >= ' . PublicationTypesEnum::TYPE_NEWS->value . '
                    and (
                        publication_translations.title ilike \'%' . $search . '%\'
                        or publication_translations.content ilike \'%' . $search . '%\'
                        or publication_translations.short_content ilike \'%' . $search . '%\'
                    )
            ';

            //Search in OGP news
            $ogpNewsItemsUnion = '
                select
                    publication.id,
                    publication_translations.title as name,
                    \'ogp_news\' as item_type,
                    \'\' as act_type_name
                from publication
                join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \'' . $locale . '\'
                where true
                    and publication.deleted_at is null
                    and publication.active = true
                    and publication.published_at >= \'' . $nowDate . '\'
                    and publication.type >= ' . PublicationTypesEnum::TYPE_OGP_NEWS->value . '
                    and (
                        publication_translations.title ilike \'%' . $search . '%\'
                        or publication_translations.content ilike \'%' . $search . '%\'
                        or publication_translations.short_content ilike \'%' . $search . '%\'
                    )
            ';

            //Search in Public consultation
            $items = \DB::select('
                select results.* from
                    (select
                        public_consultation.id,
                        public_consultation_translations.title as name,
                        \'pc\' as item_type,
                        \'\' as act_type_name
                    from public_consultation
                    join public_consultation_translations on public_consultation_translations.public_consultation_id = public_consultation.id and public_consultation_translations.locale = \'' . $locale . '\'
                    where true
                        and public_consultation.deleted_at is null
                        and public_consultation.active = 1
                        and (
                            public_consultation_translations.title ilike \'%' . $search . '%\'
                            or public_consultation_translations.description ilike \'%' . $search . '%\'
                        )
                    and public_consultation.open_from <= \'' . $nowDate . '\'
                    union all ' . $advItemsUnion . '
                    union all ' . $sdItemsUnion . '
                    union all ' . $liItemsUnion . '
                    union all ' . $prisItemsUnion . '
                    union all ' . $publicationsItemsUnion . '
                    union all ' . $newsItemsUnion . '
                    union all ' . $ogpNewsItemsUnion . ') as results
                limit ' . $perPage . '
                offset ' . $offset . '
            ');
        }

        $pageTitle = __('site.search_in_platform_page_title');

        $this->setBreadcrumbsFull(array(
            ['name' => $pageTitle, 'url' => '']
        ));
        return $this->view('site.search_results', compact('items', 'pageTitle', 'search', 'totalResults', 'page', 'defaultPaginate'));
    }
}
