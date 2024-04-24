<?php

namespace App\Http\Controllers;

use App\Enums\CalcTypesEnum;
use App\Enums\PageModulesEnum;
use App\Enums\PublicationTypesEnum;
use App\Http\Requests\SendMessageRequest;
use App\Mail\ContactFormMsg;
use App\Models\AdvisoryBoard;
use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Models\File;
use App\Models\LegalActType;
use App\Models\LegislativeInitiative;
use App\Models\Pris;
use App\Models\Publication;
use App\Models\StrategicDocument;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Enums\LegislativeInitiativeStatusesEnum;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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

    public function searchSection(Request $request)
    {
        $defaultPaginate = config('app.default_paginate');
        $search = $request->input('search') ?? '';
        $search = mb_strtolower($search);
        $section = $request->input('section') ?? '';
        $paginate = $requestFilter['paginate'] ?? config('app.default_paginate');
        $locale = app()->getLocale();
        $nowDate = Carbon::now()->format('Y-m-d');
        $nowDateTimeStamp = Carbon::now()->format('Y-m-d H:i:s');
        switch ($section)
        {
            case 'adv_board':
                $sectionName = trans_choice('custom.advisory_boards', 2);
                $items = AdvisoryBoard::select(['advisory_boards.id', 'advisory_board_translations.name'])
                    ->join('advisory_board_translations', function ($j) use($locale){
                        $j->on('advisory_board_translations.advisory_board_id', '=', 'advisory_boards.id')
                            ->where('advisory_board_translations.locale', '=', $locale);
                    })
                ->where('advisory_boards.public', '=', true)
                ->where('advisory_board_translations.name', 'ilike', '%'.$search.'%')
                ->paginate($paginate);
                break;
            case 'sd':
                $sectionName = trans_choice('custom.strategic_documents', 2);
                $items = StrategicDocument::select(['strategic_document.id', \DB::raw('strategic_document_translations.title as name')])
                    ->join('strategic_document_translations', function ($j) use($locale){
                        $j->on('strategic_document_translations.strategic_document_id', '=', 'strategic_document.id')
                            ->where('strategic_document_translations.locale', '=', $locale);
                    })
                    ->where('strategic_document.active', '=', true)
                    ->where('strategic_document_translations.title', 'ilike', '%'.$search.'%')
                    ->paginate($paginate);
                break;
            case 'pc':
                $sectionName = trans_choice('custom.public_consultations', 2);
                $items = PublicConsultation::select(['public_consultation.id', \DB::raw('public_consultation_translations.title as name')])
                    ->join('public_consultation_translations', function ($j) use($locale){
                        $j->on('public_consultation_translations.public_consultation_id', '=', 'public_consultation.id')
                            ->where('public_consultation_translations.locale', '=', $locale);
                    })
                    ->where('public_consultation.active', '=', 1)
                    ->where(function ($q) use($search){
                        $q->where('public_consultation_translations.title', 'ilike', '%'.$search.'%')
                            ->orWhere('public_consultation_translations.description', 'ilike', '%'.$search.'%');
                    })
                    ->where('public_consultation.open_from', '<=', $nowDate)
                    ->paginate($paginate);
                break;
            case 'li':
                $sectionName = trans_choice('custom.legislative_initiatives', 2);
                $items = LegislativeInitiative::select(['legislative_initiative.id', \DB::raw(__('custom.change_f').' '.__('custom.in').' || \' \' || law_translations.name as name')])
                    ->join('law', 'law.id', '=','legislative_initiative.law_id')
                    ->join('law_translations', function ($j) use($locale){
                        $j->on('law_translations.law_id', '=', 'law.id')
                            ->where('law_translations.locale', '=', $locale);
                    })
                    ->where(function ($q) use($search){
                        $q->where('legislative_initiative.description', 'ilike', '%'.$search.'%')
                            ->orWhere('legislative_initiative.law_paragraph', 'ilike', '%'.$search.'%')
                            ->orWhere('legislative_initiative.law_text', 'ilike', '%'.$search.'%');
                    })
                    ->paginate($paginate);
                break;
            case 'publications':
            case 'news':
            case 'ogp_news':
                $type = $section == 'publications' ? PublicationTypesEnum::TYPE_LIBRARY->value :
                    ($section == 'news' ? PublicationTypesEnum::TYPE_NEWS->value : PublicationTypesEnum::TYPE_OGP_NEWS->value);
                $sectionName = $section == 'publications' ? trans_choice('custom.publications', 2) :
                    ($section == 'news' ? trans_choice('custom.news', 2) : trans_choice('custom.ogp_news', 2));

                $items = PublicConsultation::select(['publication.id', \DB::raw('publication_translations.title as name')])
                    ->join('publication_translations', function ($j) use($locale){
                        $j->on('publication_translations.publication_id', '=', 'publication.id')
                            ->where('publication_translations.locale', '=', $locale);
                    })
                    ->where(function ($q) use($search){
                        $q->where('publication_translations.title', 'ilike', '%'.$search.'%')
                            ->orWhere('publication_translations.content', 'ilike', '%'.$search.'%')
                            ->orWhere('publication_translations.short_content', 'ilike', '%'.$search.'%');
                    })
                    ->where('publication.active', '=', true)
                    ->where('publication.published_at', '>=', $nowDate)
                    ->where('publication.type', '=', $type)
                    ->paginate($paginate);
                break;
            case 'pris':
                $sectionName = trans_choice('custom.pris', 2);
                $items = Pris::select(['pris.id', \DB::raw('legal_act_type_translations.name as act_type_name'), \DB::raw('legal_act_type_translations.name || \' \' || \'' . __('custom.number_symbol') . '\' || pris.doc_num || \' \' || \'' . __('custom.of') . '\' || \''.__('site.the_ministry').'\' || \' \' || \''.__('custom.from').'\' || \' \' || date_part(\'year\',pris.doc_date) || \''.__('custom.year_short').'\' as name')])
                    ->join('pris_translations', function ($j) use($locale){
                        $j->on('pris_translations.pris_id', '=', 'pris.id')
                            ->where('pris_translations.locale', '=', $locale);
                    })
                    ->join('legal_act_type', 'pris.legal_act_type_id', '=', 'legal_act_type.id')
                    ->join('legal_act_type_translations', function ($j) use($locale){
                        $j->on('legal_act_type_translations.legal_act_type_id', '=', 'legal_act_type.id')
                            ->where('legal_act_type_translations.locale', '=', $locale);
                    })
                    ->where(function ($q) use($search){
                        $q->where('pris_translations.about', 'ilike', '%'.$search.'%')
                            ->orWhere('pris_translations.legal_reason', 'ilike', '%'.$search.'%')
                            ->orWhere('pris_translations.importer', 'ilike', '%'.$search.'%');
                    })
                    ->where('pris.active', '=', 1)
                    ->where('pris.asap_last_version', '=', 1)
                    ->whereNotNull('pris.published_at')
                    ->where('pris.legal_act_type_id', '<>', LegalActType::TYPE_ARCHIVE)
                    ->paginate($paginate);
                break;
        }
        $pageTitle = __('site.search_in_platform_page_title').' '.__('custom.in').' '.__('custom.section_search').' '.$sectionName;
        $this->setBreadcrumbsFull(array(
            ['name' => $pageTitle, 'url' => ''],
            ['name' => $sectionName, 'url' => '']
        ));
        return $this->view('site.search_results_section', compact('pageTitle', 'search', 'defaultPaginate', 'items', 'sectionName'));
    }
    public function search(Request $request)
    {
        $defaultPaginate = 6;
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
                sum(result.cnt),
                sum(case when result.type = \'pc\' then result.cnt else 0 end) as pc_cnt,
                sum(case when result.type = \'adv_board\' then result.cnt else 0 end) as adv_board_cnt,
                sum(case when result.type = \'sd\' then result.cnt else 0 end) as sd_cnt,
                sum(case when result.type = \'li\' then result.cnt else 0 end) as li_cnt,
                sum(case when result.type = \'pris\' then result.cnt else 0 end) as pris_cnt,
                sum(case when result.type = \'publications\' then result.cnt else 0 end) as publications_cnt,
                sum(case when result.type = \'news\' then result.cnt else 0 end) as news_cnt,
                sum(case when result.type = \'ogp_news\' then result.cnt else 0 end) as ogp_news_cnt
            from
                (
                    select
                        count(public_consultation.id) as cnt,
                        \'pc\' as type
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
                        count(advisory_boards.id) as cnt,
                        \'adv_board\' as type
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
                            count(strategic_document.id) as cnt,
                            \'sd\' as type
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
                            count(legislative_initiative.id) as cnt,
                            \'li\' as type
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
                            count(pris.id) as cnt,
                            \'pris\' as type
                        from pris
                        join pris_translations on pris_translations.pris_id = pris.id and pris_translations.locale = \'' . $locale . '\'
                        join legal_act_type on pris.legal_act_type_id = legal_act_type.id
                        join legal_act_type_translations on legal_act_type_translations.legal_act_type_id = legal_act_type.id and legal_act_type_translations.locale = \'' . $locale . '\'
                        where true
                            and pris.deleted_at is null
                            and pris.active = 1
                            and pris.published_at  is not null
                            and pris.asap_last_version = 1
                            and pris.legal_act_type_id in (' . implode(',', [LegalActType::TYPE_DECREES, LegalActType::TYPE_DECISION, LegalActType::TYPE_PROTOCOL_DECISION, LegalActType::TYPE_DISPOSITION, LegalActType::TYPE_PROTOCOL]) . ')
                            and (
                                pris_translations.about ilike \'%' . $search . '%\'
                                or pris_translations.legal_reason ilike \'%' . $search . '%\'
                                or pris_translations.importer ilike \'%' . $search . '%\'
                            )
                    union all
                        select
                            count(publication.id) as cnt,
                            \'publications\' as type
                        from publication
                        join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \'' . $locale . '\'
                        where true
                            and publication.deleted_at is null
                            and publication.active = true
                            and publication.published_at <= \'' . $nowDate . '\'
                            and publication.type = ' . PublicationTypesEnum::TYPE_LIBRARY->value . '
                            and (
                                publication_translations.title ilike \'%' . $search . '%\'
                                or publication_translations.content ilike \'%' . $search . '%\'
                                or publication_translations.short_content ilike \'%' . $search . '%\'
                            )
                    union all
                        select
                            count(publication.id) as cnt,
                            \'news\' as type
                        from publication
                        join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \'' . $locale . '\'
                        where true
                            and publication.deleted_at is null
                            and publication.active = true
                            and publication.published_at <= \'' . $nowDate . '\'
                            and publication.type = ' . PublicationTypesEnum::TYPE_NEWS->value . '
                            and (
                                publication_translations.title ilike \'%' . $search . '%\'
                                or publication_translations.content ilike \'%' . $search . '%\'
                                or publication_translations.short_content ilike \'%' . $search . '%\'
                            )
                    union all
                        select
                            count(publication.id) as cnt,
                            \'ogp_news\' as type
                        from publication
                        join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \'' . $locale . '\'
                        where true
                            and publication.deleted_at is null
                            and publication.active = true
                            and publication.published_at <= \'' . $nowDate . '\'
                            and publication.type = ' . PublicationTypesEnum::TYPE_OGP_NEWS->value . '
                            and (
                                publication_translations.title ilike \'%' . $search . '%\'
                                or publication_translations.content ilike \'%' . $search . '%\'
                                or publication_translations.short_content ilike \'%' . $search . '%\'
                            )
                ) as result
        ');

        $totalResults = $totalCnt[0];
        $adv_board_items = $sd_items = $li_items = $pris_items = $publications_items = $news_items = $ogp_news_items = $pc_items = null;
        if(isset($totalCnt[0]) && $totalCnt[0]->sum) {
            //Search in Adv Boards
            $adv_board_items = \DB::select('
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
                limit ' . $perPage . '
                offset ' . $offset . '
            ');

            //Search in Strategic documents
            $sd_items = \DB::select('
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
                limit ' . $perPage . '
                offset ' . $offset . '
            ');

            //Search in legislative Initiative
            $li_items = \DB::select('
                select
                    legislative_initiative.id,
                    \''.__('custom.change_f').' '.__('custom.in').'\' || \' \' || law_translations.name as name,
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
                limit ' . $perPage . '
                offset ' . $offset . '
            ');

            //Search in PRIS
            $pris_items = \DB::select('
                select
                    pris.id,
                    legal_act_type_translations.name_single || \' \' || \'' . __('custom.number_symbol') . '\' || pris.doc_num || \' \' || \'' . __('custom.of') . '\' || \' \' || \''.__('site.the_ministry').'\' || \' \' || \''.__('custom.from').'\' || \' \' || date_part(\'year\',pris.doc_date) || \''.__('custom.year_short').'\' as name,
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
                    and pris.asap_last_version = 1
                    and pris.legal_act_type_id in (' . implode(',', [LegalActType::TYPE_DECREES, LegalActType::TYPE_DECISION, LegalActType::TYPE_PROTOCOL_DECISION, LegalActType::TYPE_DISPOSITION, LegalActType::TYPE_PROTOCOL]) . ')
                    and (
                        pris_translations.about ilike \'%' . $search . '%\'
                        or pris_translations.legal_reason ilike \'%' . $search . '%\'
                        or pris_translations.importer ilike \'%' . $search . '%\'
                    )
                limit ' . $perPage . '
                offset ' . $offset . '
            ');

            //Search in Publications
            $publications_items = \DB::select('
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
                    and publication.published_at <= \'' . $nowDate . '\'
                    and publication.type = ' . PublicationTypesEnum::TYPE_LIBRARY->value . '
                    and (
                        publication_translations.title ilike \'%' . $search . '%\'
                        or publication_translations.content ilike \'%' . $search . '%\'
                        or publication_translations.short_content ilike \'%' . $search . '%\'
                    )
                limit ' . $perPage . '
                offset ' . $offset . '
            ');

            //Search in News
            $news_items = \DB::select('
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
                    and publication.published_at <= \'' . $nowDate . '\'
                    and publication.type = ' . PublicationTypesEnum::TYPE_NEWS->value . '
                    and (
                        publication_translations.title ilike \'%' . $search . '%\'
                        or publication_translations.content ilike \'%' . $search . '%\'
                        or publication_translations.short_content ilike \'%' . $search . '%\'
                    )
                limit ' . $perPage . '
                offset ' . $offset . '
            ');

            //Search in OGP news
            $ogp_news_items = \DB::select('
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
                    and publication.published_at <= \'' . $nowDate . '\'
                    and publication.type = ' . PublicationTypesEnum::TYPE_OGP_NEWS->value . '
                    and (
                        publication_translations.title ilike \'%' . $search . '%\'
                        or publication_translations.content ilike \'%' . $search . '%\'
                        or publication_translations.short_content ilike \'%' . $search . '%\'
                    )
                limit ' . $perPage . '
                offset ' . $offset . '
            ');

            //Search Public consultation
            $pc_items = \DB::select('
                select
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
                    limit ' . $perPage . '
                    offset ' . $offset . '
            ');
        }

        $pageTitle = __('site.search_in_platform_page_title');

        $this->setBreadcrumbsFull(array(
            ['name' => $pageTitle, 'url' => '']
        ));
        return $this->view('site.search_results', compact('pageTitle', 'search', 'totalResults', 'page', 'defaultPaginate'
            , 'adv_board_items', 'sd_items', 'li_items', 'pris_items', 'publications_items', 'news_items'
            , 'ogp_news_items', 'pc_items'));
    }

    public function contacts(Request $request, $section = '')
    {
        $title = empty($section) ? __('site.contact_with_administrator') : __('site.contact_with_'.str_replace('-', '_', $section));
        $form = false;
        switch ($section){
            case 'public-consultations':
                $roles = [CustomRole::MODERATOR_PUBLIC_CONSULTATION];
                break;
            case 'advisory-boards':
                $roles = [CustomRole::MODERATOR_ADVISORY_BOARDS, CustomRole::MODERATOR_ADVISORY_BOARD];
                break;
            case 'strategy-documents':
                $roles = [CustomRole::MODERATOR_STRATEGIC_DOCUMENTS, CustomRole::MODERATOR_STRATEGIC_DOCUMENT];
                break;
            case 'ogp':
                $roles = [CustomRole::MODERATOR_PARTNERSHIP];
                break;
            case 'pris':
                $roles = [CustomRole::MODERATOR_PRIS];
                break;
            default:
                $form = true;
                $roles = [CustomRole::ADMIN_USER_ROLE];
        }

        $users = User::role($roles)->orderBy('first_name')->get();

        $pageTitle = trans_choice('custom.contacts', 2);
        return $this->view('site.contacts', compact('pageTitle', 'title', 'form', 'users', 'roles', 'section'));
    }

    public function sendMessage(SendMessageRequest $request)
    {
        $validated = $request->validated();

        if(config('app.env') != 'production'){
            $admins = [config('mail.local_to_mail')];
        } else{
            $admins = User::role([CustomRole::ADMIN_USER_ROLE])->get()->pluck('email')->toArray();
        }

        if(!sizeof($admins)){
            return back()->withInput()->with('danger', __('site.no_admins_for_contact'));
        }

        Mail::to($admins)->send(new ContactFormMsg($validated));

        return back()->with('success', __('site.contacts.success_send_msg'));

    }

    public function otherLinks(Request $request)
    {
        $title = __('site.footer.other_links');
        $links = array(
            ['url' => 'https://iisda.government.bg/', 'name' => __('site.footer.other_links.1'), 'logo' => 'logo-gerb.jpg'],
            ['url' => 'https://jobs.government.bg/PJobs/', 'name' => __('site.footer.other_links.2'), 'logo' => 'logo-jobs-gov.png'],
            ['url' => 'https://pitay.government.bg/', 'name' => __('site.footer.other_links.3'), 'logo' => 'logo-gerb.jpg'],
            ['url' => 'https://data.egov.bg/', 'name' => __('site.footer.other_links.4'), 'logo' => 'opendata-logo.svg'],
            ['url' => 'https://www.parliament.bg/bg/bills', 'name' => __('site.footer.other_links.5'), 'logo' => 'logo-ns.png'],
            ['url' => 'https://www.parliament.bg/bg/ncpi', 'name' => __('site.footer.other_links.6'), 'logo' => 'logo-ns.png'],
            ['url' => 'https://dv.parliament.bg/DVWeb/index.faces', 'name' => __('site.footer.other_links.7'), 'logo' => 'logo-gerb.jpg'],
        );

        $pageTitle = __('site.footer.other_links');
        $this->setBreadcrumbsFull(array(
            ['name' => __('site.footer.other_links'), 'url' => '']
        ));
        return $this->view('site.other_links', compact('pageTitle', 'title', 'links'));
    }

    public function sitemap(Request $request)
    {
        $addItems = $this->getSiteMapsItems(true);
        $addItemsPages = ceil(sizeof($addItems)/1500);
        $modifyTime = Carbon::now()->toIso8601String();
        return response()->view('site.sitemap', compact('addItemsPages', 'modifyTime'))->header('Content-Type', 'text/xml');
    }

    public function sitemapBase(Request $request)
    {
        $languages = config('available_languages');
        $formatDate = 'Y-m-d';
        $prisLegalType = LegalActType::Pris()->get();
        //Get home
        $items = array();
        foreach ($languages as $lang){
            //Get home
            if($lang != config('app.default_lang')){
                $items[] = ['url' => LaravelLocalization::localizeUrl(route('site.home'), $lang['code']), 'priority' => '1.0', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            }

            //Get Base module pages
            //Public consultation
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('public_consultation.index'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('public_consultation.report.simple'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('public_consultation.report.field_of_actions'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('public_consultation.report.field_of_actions.institution'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('public_consultation.report.institutions'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            //Impact Assessments
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('impact_assessment.forms'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('impact_assessment.form', ['form' => 'form1']), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('impact_assessment.form', ['form' => 'form2']), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('impact_assessment.form', ['form' => 'form3']), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('impact_assessment.form', ['form' => 'form4']), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('impact_assessment.tools'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('impact_assessment.tools.calc', ['calc' => CalcTypesEnum::STANDARD_COST->value]), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('impact_assessment.tools.calc', ['calc' => CalcTypesEnum::COST_EFFECTIVENESS->value]), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('impact_assessment.tools.calc', ['calc' => CalcTypesEnum::COSTS_AND_BENEFITS->value]), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('impact_assessment.tools.calc', ['calc' => CalcTypesEnum::MULTICRITERIA->value]), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('impact_assessment.executors'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            //Pris
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('pris.index'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('pris.archive'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];

            if($prisLegalType->count()){
                foreach ($prisLegalType as $i){
                    $items[] = ['url' => LaravelLocalization::localizeUrl(route('pris.category', ['category' => Str::slug($i->name)]), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
                }
            }
            //LP/OP
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('lp.index'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('op.index'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            //Legislative initiative
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('legislative_initiatives.index'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('legislative_initiatives.info'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            //OGP
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('ogp.info'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('ogp.national_action_plans'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('ogp.national_action_plans.show.old', ['id' => 1]), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('ogp.national_action_plans.show.old', ['id' => 2]), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('ogp.national_action_plans.show.old', ['id' => 3]), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('ogp.forum'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('ogp.contacts'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('ogp.news'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('ogp.events'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            //Polls
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('poll.index'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            //Strategic documents
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('strategy-documents.index'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('strategy-documents.tree'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('strategy-documents.reports'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('strategy-document.info'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('strategy-document.documents'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('strategy-document.contacts'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            //Adv boards
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('advisory-boards.index'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('advisory-boards.reports'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('advisory-boards.info'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('advisory-boards.documents'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('advisory-boards.news'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('advisory-boards.contacts'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            //Library
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('library.news'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('library.publications'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
            //Get Footer pages and static pages
            $items[] = ['url' => LaravelLocalization::localizeUrl(route('contacts'), $lang['code']), 'priority' => '0.8', 'lastmod' => Carbon::now()->startOfMonth()->format($formatDate)];
        }
        return response()->view('site.sitemap_sub', compact('items'))->header('Content-Type', 'text/xml');
    }

    public function sitemapSub(Request $request, $page)
    {
        $perPage = 1500;
        $offset = ($page * $perPage) - $perPage;
        $dbItems = $this->getSiteMapsItems(false, $offset, $perPage);
        $items = array();
        if(sizeof($dbItems)){
            foreach ($dbItems as $item) {
                switch ($item->type){
                    case 'pc':
                        $items[] = ['url' => LaravelLocalization::localizeUrl(route('public_consultation.view', ['id' => $item->id]), $item->locale), 'priority' => '0.8', 'lastmod' => $item->lastmod];
                        break;
                    case 'page':
                        $items[] = ['url' => LaravelLocalization::localizeUrl(route('page.view', ['slug' => $item->slug]), $item->locale), 'priority' => '0.8', 'lastmod' => $item->lastmod];
                        break;
                    case 'li':
                        $items[] = ['url' => LaravelLocalization::localizeUrl(route('legislative_initiatives.view', ['item' => $item->id]), $item->locale), 'priority' => '0.8', 'lastmod' => $item->lastmod];
                        break;
                    case 'pris':
                        $items[] = ['url' => LaravelLocalization::localizeUrl(route('pris.view', ['category' => Str::slug($item->slug), 'id' => $item->id]), $item->locale), 'priority' => '0.8', 'lastmod' => $item->lastmod];
                        break;
                    case 'lp':
                        $items[] = ['url' => LaravelLocalization::localizeUrl(route('lp.view', ['id' => $item->id]), $item->locale), 'priority' => '0.8', 'lastmod' => $item->lastmod];
                        break;
                    case 'op':
                        $items[] = ['url' => LaravelLocalization::localizeUrl(route('op.view', ['id' => $item->id]), $item->locale), 'priority' => '0.8', 'lastmod' => $item->lastmod];
                        break;
                    case 'sd':
                        $items[] = ['url' => LaravelLocalization::localizeUrl(route('strategy-document.view', ['id' => $item->id]), $item->locale), 'priority' => '0.8', 'lastmod' => $item->lastmod];
                        break;
                    case 'adv_board':
                        $items[] = ['url' => LaravelLocalization::localizeUrl(route('advisory-boards.view', ['item' => $item->id]), $item->locale), 'priority' => '0.8', 'lastmod' => $item->lastmod];
                        break;
                    case 'adv_board_news':
                        $items[] = ['url' => LaravelLocalization::localizeUrl(route('advisory-boards.news.details', ['item' => $item->id]), $item->locale), 'priority' => '0.8', 'lastmod' => $item->lastmod];
                        break;
                    case 'ogp_news':
                        $items[] = ['url' => LaravelLocalization::localizeUrl(route('ogp.news.details', ['item' => $item->id]), $item->locale), 'priority' => '0.8', 'lastmod' => $item->lastmod];
                        break;
                    case 'page_ogp':
                        $items[] = ['url' => LaravelLocalization::localizeUrl(route('ogp.library.view', ['slug' => $item->slug]), $item->locale), 'priority' => '0.8', 'lastmod' => $item->lastmod];
                        break;
                    case 'news':
                        $items[] = ['url' => LaravelLocalization::localizeUrl(route('library.details', ['type' => PublicationTypesEnum::TYPE_NEWS->value, 'id' => $item->id]), $item->locale), 'priority' => '0.8', 'lastmod' => $item->lastmod];
                        break;
                    case 'publication':
                        $items[] = ['url' => LaravelLocalization::localizeUrl(route('library.details', ['type' => PublicationTypesEnum::TYPE_LIBRARY->value, 'id' => $item->id]), $item->locale), 'priority' => '0.8', 'lastmod' => $item->lastmod];
                        break;
                }
            }
        }
        return response()->view('site.sitemap_sub', compact('items'))->header('Content-Type', 'text/xml');
    }

    private function getSiteMapsItems($cnt = false, $offset = 0, $perPage = 1500)
    {
        $today = Carbon::now()->format('Y-m-d');
        return \DB::select(
            '
                    select
                        A.*
                    from (
                        select
                            \'page\' as type,
                            page.slug as slug,
                            page.id,
                            coalesce(page.updated_at, page.created_at) as lastmod,
                            \'bg\' as locale
                        from page
                        join page_translations on page_translations.page_id = page.id and page_translations.locale = \'bg\'
                        where
                            page.deleted_at is null
                            and page.active = 1
                            and page.is_system = 0
                            and page.module_enum is null
                        union all
                            select
                                \'page\' as type,
                                page.slug as slug,
                                page.id,
                                coalesce(page.updated_at, page.created_at) as lastmod,
                                \'en\' as locale
                            from page
                            join page_translations on page_translations.page_id = page.id and page_translations.locale = \'en\'
                            where
                                page.deleted_at is null
                                and page.active = 1
                                and page.is_system = 0
                                and page.module_enum is null
                        union all
                            select
                                \'pc\' as type,
                                \'\' as slug,
                                public_consultation.id,
                                coalesce(public_consultation.updated_at, public_consultation.created_at) as lastmod,
                                \'bg\' as locale
                            from public_consultation
                            join public_consultation_translations on public_consultation_translations.public_consultation_id = public_consultation.id and public_consultation_translations.locale = \'bg\'
                            where
                                public_consultation.deleted_at is null
                                and public_consultation.active = 1
                                and public_consultation.open_from <= \''.$today.'\'
                        union all
                            select
                                \'pc\' as type,
                                \'\' as slug,
                                public_consultation.id,
                                coalesce(public_consultation.updated_at, public_consultation.created_at) as lastmod,
                                \'en\' as locale
                            from public_consultation
                            join public_consultation_translations on public_consultation_translations.public_consultation_id = public_consultation.id and public_consultation_translations.locale = \'en\'
                            where
                                public_consultation.deleted_at is null
                                and public_consultation.active = 1
                                and public_consultation.open_from <= \''.$today.'\'
                        union all
                            select
                                \'li\' as type,
                                \'\' as slug,
                                legislative_initiative.id,
                                coalesce(legislative_initiative.updated_at, legislative_initiative.created_at) as lastmod,
                                \'bg\' as locale
                            from legislative_initiative
                            where
                                legislative_initiative.deleted_at is null
                        union all
                            select
                                \'li\' as type,
                                \'\' as slug,
                                legislative_initiative.id,
                                coalesce(legislative_initiative.updated_at, legislative_initiative.created_at) as lastmod,
                                \'en\' as locale
                            from legislative_initiative
                            where
                                legislative_initiative.deleted_at is null
                        union all
                            select
                                \'pris\' as type,
                                legal_act_type_translations.name as slug,
                                pris.id,
                                coalesce(pris.updated_at, pris.created_at) as lastmod,
                                \'bg\' as locale
                            from pris
                            join pris_translations on pris_translations.pris_id = pris.id and pris_translations.locale = \'bg\'
                            join legal_act_type on legal_act_type.id = pris.legal_act_type_id
                            join legal_act_type_translations on legal_act_type_translations.legal_act_type_id = legal_act_type.id and legal_act_type_translations.locale = \'bg\'
                            where
                                pris.active = 1
                                and pris.deleted_at is null
                                and pris.asap_last_version = 1
                        union all
                            select
                                \'pris\' as type,
                                legal_act_type_translations.name as slug,
                                pris.id,
                                coalesce(pris.updated_at, pris.created_at) as lastmod,
                                \'en\' as locale
                            from pris
                            join pris_translations on pris_translations.pris_id = pris.id and pris_translations.locale = \'en\'
                            join legal_act_type on legal_act_type.id = pris.legal_act_type_id
                            join legal_act_type_translations on legal_act_type_translations.legal_act_type_id = legal_act_type.id and legal_act_type_translations.locale = \'en\'
                            where
                                pris.active = 1
                                and pris.deleted_at is null
                                and pris.asap_last_version = 1
                        union all
                            select
                                \'lp\' as type,
                                \'\' as slug,
                                legislative_program.id,
                                coalesce(legislative_program.updated_at, legislative_program.created_at) as lastmod,
                                \'bg\' as locale
                            from legislative_program
                            where
                                legislative_program.public = 1
                                and legislative_program.deleted_at is null
                        union all
                            select
                                \'lp\' as type,
                                \'\' as slug,
                                legislative_program.id,
                                coalesce(legislative_program.updated_at, legislative_program.created_at) as lastmod,
                                \'en\' as locale
                            from legislative_program
                            where
                                legislative_program.public = 1
                                and legislative_program.deleted_at is null
                        union all
                            select
                                \'op\' as type,
                                \'\' as slug,
                                operational_program.id,
                                coalesce(operational_program.updated_at, operational_program.created_at) as lastmod,
                                 \'bg\' as locale
                            from operational_program
                            where
                                operational_program.public = 1
                                and operational_program.deleted_at is null
                        union all
                            select
                                \'op\' as type,
                                \'\' as slug,
                                operational_program.id,
                                coalesce(operational_program.updated_at, operational_program.created_at) as lastmod,
                                 \'en\' as locale
                            from operational_program
                            where
                                operational_program.public = 1
                                and operational_program.deleted_at is null
                        union all
                            select
                                \'sd\' as type,
                                \'\' as slug,
                                strategic_document.id,
                                coalesce(strategic_document.updated_at, strategic_document.created_at) as lastmod,
                                \'bg\' as locale
                            from strategic_document
                            join strategic_document_translations on strategic_document_translations.strategic_document_id = strategic_document.id and strategic_document_translations.locale = \'bg\'
                            where
                                strategic_document.active = true
                                and strategic_document.deleted_at is null
                                and strategic_document.parent_document_id is null
                        union all
                            select
                                \'sd\' as type,
                                \'\' as slug,
                                strategic_document.id,
                                coalesce(strategic_document.updated_at, strategic_document.created_at) as lastmod,
                                \'en\' as locale
                            from strategic_document
                            join strategic_document_translations on strategic_document_translations.strategic_document_id = strategic_document.id and strategic_document_translations.locale = \'en\'
                            where
                                strategic_document.active = true
                                and strategic_document.deleted_at is null
                                and strategic_document.parent_document_id is null
                        union all
                            select
                                \'adv_board\' as type,
                                \'\' as slug,
                                advisory_boards.id,
                                coalesce(advisory_boards.updated_at, advisory_boards.created_at) as lastmod,
                                \'bg\' as locale
                            from advisory_boards
                            join advisory_board_translations on advisory_board_translations.advisory_board_id = advisory_boards.id and advisory_board_translations.locale = \'bg\'
                            where
                                advisory_boards.active = true
                                and advisory_boards.deleted_at is null
                                and advisory_boards.public = true
                        union all
                            select
                                \'adv_board\' as type,
                                \'\' as slug,
                                advisory_boards.id,
                                coalesce(advisory_boards.updated_at, advisory_boards.created_at) as lastmod,
                                \'en\' as locale
                            from advisory_boards
                            join advisory_board_translations on advisory_board_translations.advisory_board_id = advisory_boards.id and advisory_board_translations.locale = \'en\'
                            where
                                advisory_boards.active = true
                                and advisory_boards.deleted_at is null
                                and advisory_boards.public = true
                        union all
                            select
                                \'adv_board_news\' as type,
                                \'\' as slug,
                                publication.id,
                                coalesce(publication.updated_at, publication.created_at) as lastmod,
                                \'bg\' as locale
                            from publication
                            join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \'bg\'
                            where
                                publication.active = true
                                and publication.deleted_at is null
                                and publication.published_at >= \''.$today.'\'
                                and publication.type = '.PublicationTypesEnum::TYPE_ADVISORY_BOARD->value.'
                        union all
                            select
                                \'adv_board_news\' as type,
                                \'\' as slug,
                                publication.id,
                                coalesce(publication.updated_at, publication.created_at) as lastmod,
                                \'en\' as locale
                            from publication
                            join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \'en\'
                            where
                                publication.active = true
                                and publication.deleted_at is null
                                and publication.published_at >= \''.$today.'\'
                                and publication.type = '.PublicationTypesEnum::TYPE_ADVISORY_BOARD->value.'
                        union all
                            select
                                \'ogp_news\' as type,
                                \'\' as slug,
                                publication.id,
                                coalesce(publication.updated_at, publication.created_at) as lastmod,
                                \'bg\' as locale
                            from publication
                            join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \'bg\'
                            where
                                publication.active = true
                                and publication.deleted_at is null
                                and publication.published_at >= \''.$today.'\'
                                and publication.type = '.PublicationTypesEnum::TYPE_OGP_NEWS->value.'
                        union all
                            select
                                \'ogp_news\' as type,
                                \'\' as slug,
                                publication.id,
                                coalesce(publication.updated_at, publication.created_at) as lastmod,
                                \'en\' as locale
                            from publication
                            join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \'en\'
                            where
                                publication.active = true
                                and publication.deleted_at is null
                                and publication.published_at >= \''.$today.'\'
                                and publication.type = '.PublicationTypesEnum::TYPE_OGP_NEWS->value.'
                        union all
                            select
                                \'page_ogp\' as type,
                                page.slug as slug,
                                page.id,
                                coalesce(page.updated_at, page.created_at) as lastmod,
                                \'bg\' as locale
                            from page
                            join page_translations on page_translations.page_id = page.id and page_translations.locale = \'bg\'
                            where
                                page.deleted_at is null
                                and page.active = 1
                                and page.is_system = 0
                                and page.module_enum = '.PageModulesEnum::MODULE_OGP->value.'
                        union all
                            select
                                \'page_ogp\' as type,
                                page.slug as slug,
                                page.id,
                                coalesce(page.updated_at, page.created_at) as lastmod,
                                \'en\' as locale
                            from page
                            join page_translations on page_translations.page_id = page.id and page_translations.locale = \'en\'
                            where
                                page.deleted_at is null
                                and page.active = 1
                                and page.is_system = 0
                                and page.module_enum = '.PageModulesEnum::MODULE_OGP->value.'
                        union all
                            select
                                \'news\' as type,
                                \'\' as slug,
                                publication.id,
                                coalesce(publication.updated_at, publication.created_at) as lastmod,
                                \'bg\' as locale
                            from publication
                            join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \'bg\'
                            where
                                publication.active = true
                                and publication.deleted_at is null
                                and publication.published_at >= \''.$today.'\'
                                and publication.type = '.PublicationTypesEnum::TYPE_NEWS->value.'
                        union all
                            select
                                \'news\' as type,
                                \'\' as slug,
                                publication.id,
                                coalesce(publication.updated_at, publication.created_at) as lastmod,
                                \'en\' as locale
                            from publication
                            join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \'en\'
                            where
                                publication.active = true
                                and publication.deleted_at is null
                                and publication.published_at >= \''.$today.'\'
                                and publication.type = '.PublicationTypesEnum::TYPE_NEWS->value.'
                        union all
                            select
                                \'publication\' as type,
                                \'\' as slug,
                                publication.id,
                                coalesce(publication.updated_at, publication.created_at) as lastmod,
                                \'bg\' as locale
                            from publication
                            join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \'bg\'
                            where
                                publication.active = true
                                and publication.deleted_at is null
                                and publication.published_at >= \''.$today.'\'
                                and publication.type = '.PublicationTypesEnum::TYPE_LIBRARY->value.'
                        union all
                            select
                                \'publication\' as type,
                                \'\' as slug,
                                publication.id,
                                coalesce(publication.updated_at, publication.created_at) as lastmod,
                                \'en\' as locale
                            from publication
                            join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \'en\'
                            where
                                publication.active = true
                                and publication.deleted_at is null
                                and publication.published_at >= \''.$today.'\'
                                and publication.type = '.PublicationTypesEnum::TYPE_LIBRARY->value.'
                    ) A
                    '.(!$cnt ? ((isset($offset) && $offset ? ' offset '.$offset : '').' limit '.$perPage) : '').'
                    '
        );
    }
}
