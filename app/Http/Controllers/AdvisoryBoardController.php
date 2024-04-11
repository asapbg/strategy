<?php

namespace App\Http\Controllers;

use App\Enums\PublicationTypesEnum;
use App\Exports\AdvBoardReportExport;
use App\Models\AdvisoryActType;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardCustom;
use App\Models\AdvisoryBoardFunction;
use App\Models\AdvisoryBoardMeeting;
use App\Models\AdvisoryChairmanType;
use App\Models\AuthorityAdvisoryBoard;
use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Models\FieldOfAction;
use App\Models\Page;
use App\Models\Publication;
use App\Models\Setting;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class AdvisoryBoardController extends Controller
{
    private $pageTitle;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->title_singular = trans_choice('custom.advisory_boards', 2);
        $this->pageTitle = trans_choice('custom.advisory_boards', 2);
        $this->setSlider(trans_choice('custom.advisory_boards', 2), AdvisoryBoard::DEFAULT_HEADER_IMG);
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $rssUrl = config('feed.feeds.adv_boards.url');

        $groupOptions = array(
            ['value' => '', 'name' => ''],
            ['value' => 'fieldOfAction', 'name' => trans_choice('custom.field_of_actions', 1)],
            ['value' => 'authority', 'name' => __('custom.type_of_governing')],
            ['value' => 'chairmanType', 'name' => trans_choice('custom.advisory_chairman_type', 1)],
            ['value' => 'npo', 'name' => __('custom.presence_npo_representative')],
            ['value' => 'actOfCreation', 'name' => __('validation.attributes.act_of_creation')],
        );

        $rf = $request->all();
        $requestGroupBy = $rf['groupBy'] ?? null;

        $requestFilter = $request->all();
        //Filter
        $filter = $this->boardFilters($request);
        if( !$request->ajax() && is_null($request->input('status'))) {
            $filter['status']['value'] = 1;
            $requestFilter['status'] = 1;
        }
        //Sorter
        $sorter = $this->boardSorters();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'active';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');
        $paginate = $requestFilter['paginate'] ?? AdvisoryBoard::PAGINATE;

        $orderByName = !isset($requestFilter['status']) || $requestFilter['status'] == '';

        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        $pageTitle = $this->pageTitle;

        $items = AdvisoryBoard::select('advisory_boards.*')
            ->with(['policyArea', 'policyArea.translations', 'translations', 'moderators',
                'authority', 'authority.translations', 'advisoryChairmanType', 'advisoryChairmanType.translations',
                'advisoryActType', 'advisoryActType.translations'])
            ->leftJoin('advisory_board_translations', function ($j){
                $j->on('advisory_board_translations.advisory_board_id', '=', 'advisory_boards.id')
                    ->where('advisory_board_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('field_of_actions', 'field_of_actions.id', '=', 'advisory_boards.policy_area_id')
            ->leftJoin('field_of_action_translations', function ($j){
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('authority_advisory_board', 'authority_advisory_board.id', '=', 'advisory_boards.authority_id')
            ->leftJoin('authority_advisory_board_translations', function ($j){
                $j->on('authority_advisory_board_translations.authority_advisory_board_id', '=', 'authority_advisory_board.id')
                    ->where('authority_advisory_board_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('advisory_act_type', 'advisory_act_type.id', '=', 'advisory_boards.advisory_act_type_id')
            ->leftJoin('advisory_act_type_translations', function ($j){
                $j->on('advisory_act_type_translations.advisory_act_type_id', '=', 'advisory_act_type.id')
                    ->where('advisory_act_type_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('advisory_chairman_type', 'advisory_chairman_type.id', '=', 'advisory_boards.advisory_chairman_type_id')
            ->leftJoin('advisory_chairman_type_translations', function ($j){
                $j->on('advisory_chairman_type_translations.advisory_chairman_type_id', '=', 'advisory_chairman_type.id')
                    ->where('advisory_chairman_type_translations.locale', '=', app()->getLocale());
            })
            ->where('public', true)
            ->FilterBy($requestFilter)
            ->when($requestGroupBy, function ($query) use($requestGroupBy){
                if($requestGroupBy == 'fieldOfAction') {
                    return $query->orderBy('field_of_action_translations.name');
                } elseif($requestGroupBy == 'authority') {
                    return $query->orderBy('authority_advisory_board_translations.name');
                } elseif($requestGroupBy == 'chairmanType') {
                    return $query->orderBy('advisory_chairman_type_translations.name');
                } elseif($requestGroupBy == 'npo') {
                    return $query->orderBy('advisory_boards.has_npo_presence');
                } elseif($requestGroupBy == 'actOfCreation') {
                    return $query->orderBy('advisory_act_type_translations.name');
                }
            })
            ->orderBy('advisory_boards.active', 'desc')
            ->orderBy('advisory_board_translations.name')
//            ->when($orderByName, function ($query) {
//                return $query->orderBy('advisory_boards.active', 'desc')
//                    ->orderBy('advisory_board_translations.name');
//            })
//            ->orderBy('advisory_boards.active', 'desc')
            ->SortedBy($sort,$sortOrd)
            ->paginate($paginate);
        $subscribeFilter = $requestFilter;
        if(isset($subscribeFilter['status'])){
            $subscribeFilter['status'] = str($subscribeFilter['status']);
        }
        $hasSubscribeEmail = $this->hasSubscription(null, AdvisoryBoard::class, $subscribeFilter);
        $hasSubscribeRss = false;

        if( $request->ajax() ) {
            return view('site.advisory-boards.list', compact('filter','sorter', 'items', 'rf', 'groupOptions', 'hasSubscribeEmail', 'hasSubscribeRss', 'requestFilter', 'rssUrl'));
        }

        return $this->view('site.advisory-boards.index', compact('filter', 'sorter', 'items', 'pageTitle', 'defaultOrderBy', 'defaultDirection', 'groupOptions', 'hasSubscribeEmail', 'hasSubscribeRss', 'requestFilter', 'rssUrl'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd('store');
    }

    /**
     * Display the specified resource.
     *
     * @param AdvisoryBoard $item
     *
     * @return View
     */
    public function show(AdvisoryBoard $item)
    {
        $item = AdvisoryBoard::where('id', $item->id)
            ->with(['customSections' => function ($query) {
                $query->with(['files', 'translations']);
            }, 'npos' => function ($query) {
                $query->with('translations');
            }, 'members' => function($query) {
                $query->with(['translations', 'institution']);
            }, 'meetings' => function($query) {
                $query->where('next_meeting', '>=', Carbon::now()->startOfYear())
                    ->with(['translations', 'siteFiles'])->orderBy('next_meeting', 'desc');
            }, 'secretariat' => function($query) {
                $query->with(['translations', 'siteFiles']);
            }, 'workingProgram' => function($query) {
                $query->with(['translations', 'siteFiles']);
        }, 'policyArea'])->first();

        $nextMeeting = AdvisoryBoardMeeting::where('advisory_board_id', $item->id)
            ->where('next_meeting' ,'>', Carbon::now())
            ->orderBy('next_meeting', 'asc')
            ->get()->first();

        $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();
        $pageTitle = $item->name;
        $this->title_singular = $item->name;
        $this->setSlider($item->name, $item->headerImg);
        $this->composeBreadcrumbs($item, array(['name' => __('custom.main_information'), 'url' => '']));
        return $this->view('site.advisory-boards.view', compact('item', 'customSections', 'pageTitle', 'nextMeeting'));
    }

    public function showSection(Request $request, AdvisoryBoard $item, $sectionId = 0)
    {
        $section = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->where('id', $sectionId)->first();
        $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();
        if(!$section) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $this->title_singular = $pageTitle = $item->name;
        $this->setSlider($item->name, $item->headerImg);
        $this->composeBreadcrumbs($item, array(['name' => $section->title, 'url' => '']));
        return $this->view('site.advisory-boards.view_section', compact('item', 'section', 'customSections', 'pageTitle'));
    }

    public function archiveMeetings(Request $request, AdvisoryBoard $item)
    {
        $requestFilter = $request->all();
        $paginate = $request->filled('paginate') ? $request->get('paginate') : AdvisoryBoard::PAGINATE;

        $itemsCalendar = array();
        $itemsCalendarDB = AdvisoryBoardMeeting::with(['translations'])->where('advisory_board_id', $item->id)->get();
        if($itemsCalendarDB->count()) {
            foreach ($itemsCalendarDB as $event) {
                $itemsCalendar[] = array(
                    "id" => $event->id,
                    "title" => trans_choice('custom.meetings', 1),
                    "description" => $event->description ? strip_tags($event->description) : '---',
                    "start" => Carbon::parse($event->next_meeting)->startOfDay()->format('Y-m-d H:i:s'),
                    "end" => Carbon::parse($event->next_meeting)->endOfDay()->format('Y-m-d H:i:s'),
                    "backgroundColor" => (Carbon::parse($event->next_meeting)->startOfDay()->format('Y-m-d') > Carbon::now()->startOfDay()->format('Y-m-d') ? '#00a65a' : '#00c0ef'),
                    "borderColor" => (Carbon::parse($event->next_meeting)->startOfDay()->format('Y-m-d') > Carbon::now()->startOfDay()->format('Y-m-d') ? '#00a65a' : '#00c0ef')
                );
            }
        }
        if(!isset($requestFilter['to'])) {
            $requestFilter['to'] = Carbon::now()->startOfYear();
        }
        $filter = $this->archiveFilters($request);
        $pageTitle = $item->name;
        $this->setSlider($item->name, $item->headerImg);
        $items = AdvisoryBoardMeeting::with(['translations'])
            ->where('advisory_board_id', $item->id)
            ->where('next_meeting', '<', Carbon::now()->startOfYear()->format('Y-m-d H:i:s'))
            ->orderBy('next_meeting', 'desc')
            ->FilterBy($requestFilter)
            ->get();
//            ->paginate($paginate);

        $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();
        if( $request->ajax() ) {
            return view('site.advisory-boards.archive_meeting_list', compact('filter','items', 'item', 'itemsCalendar'));
        }

        $this->composeBreadcrumbs($item, array(['name' => __('custom.archive').' '.__('custom.meetings_and_decisions'), 'url' => '']));
        return $this->view('site.advisory-boards.archive_meeting', compact('filter','items', 'pageTitle', 'item', 'customSections', 'itemsCalendar'));
    }

    public function archiveWorkPrograms(Request $request, AdvisoryBoard $item)
    {
        $requestFilter = $request->all();
        $paginate = $request->filled('paginate') ? $request->get('paginate') : AdvisoryBoard::PAGINATE;

        if(!isset($requestFilter['to'])) {
            $requestFilter['to'] = Carbon::now()->startOfYear();
        }
        $filter = $this->archiveFilters($request);
        $pageTitle = $item->name;
        $this->setSlider($item->name, $item->headerImg);
        $items = AdvisoryBoardFunction::with(['translations'])
            ->where('advisory_board_id', $item->id)
//            ->with(['translations', 'siteFiles', 'siteFiles.versions'])
            ->where('working_year', '<', Carbon::now()->startOfYear()->format('Y-m-d H:i:s'))
            ->FilterBy($requestFilter)
            ->orderBy('working_year', 'desc')
            ->paginate($paginate);
        $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();

        if( $request->ajax() ) {
            return view('site.advisory-boards.archive_wotk_programs_list', compact('filter','items', 'item'));
        }
        $this->composeBreadcrumbs($item, array(['name' => __('custom.archive').' '.__('custom.work_programs'), 'url' => '']));
        return $this->view('site.advisory-boards.archive_work_programs', compact('filter','items', 'pageTitle', 'item', 'customSections'));
    }

    public function itemNews(Request $request, AdvisoryBoard $item)
    {
        $news = Publication::select('publication.*')
            ->ActivePublic()
            ->with(['translations', 'category', 'category.translations', 'mainImg'])
            ->leftJoin('publication_translations', function ($j){
                $j->on('publication_translations.publication_id', '=', 'publication.id')
                    ->where('publication_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('field_of_actions', 'field_of_actions.id', '=', 'publication.publication_category_id')
            ->leftJoin('field_of_action_translations', function ($j){
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->where(function ($q) use ($item){
                $q->where('publication.advisory_boards_id', $item->id)
                    ->orWhereIn('is_adv_board_user', ($item->moderators->count() ? $item->moderators->pluck('id')->toArray() : []));
            })
            ->orderBy('published_at', 'desc')
            ->GroupBy('publication.id', 'publication_translations.id', 'field_of_action_translations.id')
            ->get();

        $pageTitle = $item->name;
        $this->setSlider($item->name, $item->headerImg);
        $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();
        $this->composeBreadcrumbs($item, array(['name' => trans_choice('custom.news', 2), 'url' => '']));
        return $this->view('site.advisory-boards.view_news', compact('item', 'news', 'pageTitle', 'customSections'));
    }

    public function itemNewsDetails(Request $request, AdvisoryBoard $item, Publication $news){
        $pageTitle = $item->name;
        $this->setSeo($news->meta_title, $news->meta_description, $news->meta_keyword);
        $publication = $news;
        $this->setSlider($item->name, $item->headerImg);
        $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();
        $this->composeBreadcrumbs($item,array(
            ['name' => trans_choice('custom.news', 2), 'url' => route('advisory-boards.view.news', $item)],
            ['name' => $news->title, 'url' => '']
        ));
        return $this->view('site.advisory-boards.view_news_details', compact('item', 'publication', 'pageTitle', 'customSections'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     *
     * @param AdvisoryBoard $advisoryBoard
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(AdvisoryBoard $advisoryBoard)
    {
        dd('edit');
    }

    /**
     * Update the specified resource in storage.
     *
     *
     * @param \Illuminate\Http\Request $request
     * @param AdvisoryBoard            $advisoryBoard
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdvisoryBoard $advisoryBoard)
    {
        dd('update');
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @param AdvisoryBoard $advisoryBoard
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvisoryBoard $advisoryBoard)
    {
        dd('destroy');
    }

    public function contacts(Request $request, $itemId = null)
    {
        if($itemId) {
            $item = AdvisoryBoard::with(['translations', 'moderators'])->find($itemId);
//            $item = AdvisoryBoard::with(['translations', 'moderators', 'moderators.user', 'moderatorInformation', 'moderatorInformation.translations', 'moderatorInformation.files'])->find($itemId);
            if(!$item){
                abort(404);
            }
            $pageTitle = $item->name;
            $this->setSlider($item->name, $item->headerImg);
            $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();
            $this->composeBreadcrumbs($item, array(['name' => trans_choice('custom.contacts', 2), 'url' => '']));
            return $this->view('site.advisory-boards.contacts_inner', compact('pageTitle', 'item', 'customSections'));
        } else{
            $pageTitle = $this->pageTitle;
            $moderators = User::role([CustomRole::MODERATOR_ADVISORY_BOARDS, CustomRole::MODERATOR_ADVISORY_BOARD])->get();
            return $this->view('site.advisory-boards.contacts', compact('moderators', 'pageTitle'));
        }
    }

    public function news(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->newsFilters($request);
        //Sorter
        $sorter = $this->sorters();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'publishDate';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');

        $paginate = $requestFilter['paginate'] ?? Publication::PAGINATE;
        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        $pageTitle = $this->pageTitle;
        $items = Publication::select('publication.*')
            ->ActivePublic()
            ->with(['translations', 'category', 'category.translations'])
            ->leftJoin('publication_translations', function ($j){
                $j->on('publication_translations.publication_id', '=', 'publication.id')
                    ->where('publication_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('field_of_actions', 'field_of_actions.id', '=', 'publication.publication_category_id')
            ->leftJoin('field_of_action_translations', function ($j){
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->FilterBy($requestFilter)
            ->where('publication.type', PublicationTypesEnum::TYPE_ADVISORY_BOARD->value)
//            ->where(function ($q){
//                $q->whereNotNull('publication.advisory_boards_id')
//                    ->orWhere('is_adv_board_user', 1);
//            })
            ->SortedBy($sort,$sortOrd)
            ->GroupBy('publication.id', 'publication_translations.id', 'field_of_action_translations.id')
            ->paginate($paginate);

        if( $request->ajax() ) {
            return view('site.advisory-boards.main_news_list', compact('filter','sorter', 'items'));
        }

        return $this->view('site.advisory-boards.main_news', compact('filter','sorter', 'items', 'defaultOrderBy', 'defaultDirection', 'pageTitle'));
    }

    public function newsDetails(Request $request, Publication $item){
        $pageTitle = trans_choice('custom.advisory_boards', 2);
        $this->setSeo($item->meta_title, $item->meta_description, $item->meta_keyword);
        $publication = $item;
//        $this->setSlider(trans_choice('custom.advisory_boards', 2), $item->headerImg);
        return $this->view('site.advisory-boards.main_news_details', compact('publication', 'pageTitle'));
    }

    public function documents()
    {
        $page = Page::with(['files' => function($q) {
                $q->where('locale', '=', app()->getLocale());
            }])
            ->where('system_name', '=', Page::ADV_BOARD_DOCUMENTS)
            ->first();
        if(!$page){
            abort(404);
        }
        $pageTitle = $page->name;
        $this->setSeo($page->meta_title, $page->meta_description, $page->meta_keyword);
        return $this->view('site.advisory-boards.page', compact('page', 'pageTitle'));
    }

    public function info()
    {
        $page = Page::with(['files' => function($q) {
            $q->where('locale', '=', app()->getLocale());
        }])
            ->where('system_name', '=', Page::ADV_BOARD_INFO)
            ->first();
        if(!$page){
            abort(404);
        }
        $pageTitle = $page->name;
        $this->setSeo($page->meta_title, $page->meta_description, $page->meta_keyword);
        return $this->view('site.advisory-boards.page', compact('page', 'pageTitle'));
    }

    public function reports(Request $request)
    {
        //Filter
        $rf = $request->all();
        $requestGroupBy = $rf['groupBy'] ?? null;
        //Filter
        $requestFilter = $request->all();
        if(empty($rf)){
            $requestFilter['status'] = 'active';
        }
        $filter = $this->filtersReport($request, $rf);
        //Sorter
        $sorter = $this->sortersReport();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'status';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');

        $paginate = $requestFilter['paginate'] ?? config('app.default_paginate');
        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        $selectColumns = ['advisory_boards.*'];
        $searchMeetings = (isset($requestFilter['meetingFrom']) && !empty($requestFilter['meetingFrom'])) || (isset($requestFilter['meetingTo']) && !empty($requestFilter['meetingTo']));
        if($searchMeetings){
            $selectColumns[] = DB::raw('count(advisory_board_meetings.id) as meetings');
        }
        $q = AdvisoryBoard::select($selectColumns)
            ->with(['policyArea', 'policyArea.translations', 'translations', 'moderators',
                'authority', 'authority.translations', 'advisoryChairmanType', 'advisoryChairmanType.translations',
                'advisoryActType', 'advisoryActType.translations'])
            ->leftJoin('advisory_board_translations', function ($j){
                $j->on('advisory_board_translations.advisory_board_id', '=', 'advisory_boards.id')
                    ->where('advisory_board_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('field_of_actions', 'field_of_actions.id', '=', 'advisory_boards.policy_area_id')
            ->leftJoin('field_of_action_translations', function ($j){
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('authority_advisory_board', 'authority_advisory_board.id', '=', 'advisory_boards.authority_id')
            ->leftJoin('authority_advisory_board_translations', function ($j){
                $j->on('authority_advisory_board_translations.authority_advisory_board_id', '=', 'authority_advisory_board.id')
                    ->where('authority_advisory_board_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('advisory_act_type', 'advisory_act_type.id', '=', 'advisory_boards.advisory_act_type_id')
            ->leftJoin('advisory_act_type_translations', function ($j){
                $j->on('advisory_act_type_translations.advisory_act_type_id', '=', 'advisory_act_type.id')
                    ->where('advisory_act_type_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('advisory_chairman_type', 'advisory_chairman_type.id', '=', 'advisory_boards.advisory_chairman_type_id')
            ->leftJoin('advisory_chairman_type_translations', function ($j){
                $j->on('advisory_chairman_type_translations.advisory_chairman_type_id', '=', 'advisory_chairman_type.id')
                    ->where('advisory_chairman_type_translations.locale', '=', app()->getLocale());
            });

            if($searchMeetings){
                $q->leftJoin('advisory_board_meetings', function ($j){
                    $j->on('advisory_board_meetings.advisory_board_id', '=', 'advisory_boards.id')
                        ->whereNull('advisory_board_meetings.deleted_at');
                });
            }

            $q->where('public', true)
            ->FilterBy($requestFilter)
            ->when($requestGroupBy, function ($query) use($requestGroupBy){
                if($requestGroupBy == 'fieldOfAction') {
                    return $query->orderBy('field_of_action_translations.name');
                } elseif($requestGroupBy == 'authority') {
                    return $query->orderBy('authority_advisory_board_translations.name');
                } elseif($requestGroupBy == 'chairmanType') {
                    return $query->orderBy('advisory_chairman_type_translations.name');
                } elseif($requestGroupBy == 'npo') {
                    return $query->orderBy('advisory_boards.has_npo_presence');
                } elseif($requestGroupBy == 'actOfCreation') {
                    return $query->orderBy('advisory_act_type_translations.name');
                }
            })
            ->orderBy('advisory_boards.active', 'desc')
            ->orderBy('advisory_board_translations.name', 'asc')
            ->SortedBy($sort,$sortOrd);
        if($searchMeetings){
            $q->groupBy('advisory_boards.id');
        }

        if($request->input('export_excel') || $request->input('export_pdf')){
            $items = $q->get();
            $exportData = [
                'title' => __('custom.adv_board_report_title'),
                'rows' => $items,
                'searchMeetings' => $searchMeetings
            ];

            $fileName = 'adv_report_'.Carbon::now()->format('Y_m_d_H_i_s');
            if($request->input('export_pdf')){
                ini_set('max_execution_time', 60);
                $pdf = PDF::loadView('exports.adv_report', ['data' => $exportData, 'isPdf' => true])->setPaper('a4', 'landscape');
                return $pdf->download($fileName.'.pdf');
            } else{
                return Excel::download(new AdvBoardReportExport($exportData), $fileName.'.xlsx');
            }
        } else{
            $items = $q->paginate($paginate);
        }

        if( $request->ajax() ) {
            return view('site.advisory-boards.list_report', compact('filter','sorter', 'items', 'rf', 'searchMeetings'));
        }

        $pageTitle = trans('custom.adv_board_report_title');
        $this->composeBreadcrumbs(null, array(['name' => __('custom.adv_board_report_title'), 'url' => '']));

        return $this->view('site.advisory-boards.report', compact('filter','sorter', 'items', 'pageTitle', 'rf', 'defaultOrderBy', 'defaultDirection', 'searchMeetings'));
    }

    private function sorters()
    {
        return array(
            'category' => ['class' => 'col-md-3', 'label' => trans_choice('custom.category', 1)],
            'title' => ['class' => 'col-md-3', 'label' => __('custom.title')],
            'publishDate' => ['class' => 'col-md-3', 'label' => __('custom.date_published')],
        );
    }

    private function archiveFilters($request)
    {
        return array(
            'from' => array(
                'type' => 'datepicker',
                'value' => $request->input('from'),
                'label' => __('custom.from_date'),
                'col' => 'col-md-12'
            ),
            'to' => array(
                'type' => 'datepicker',
                'value' => $request->input('to'),
                'label' => __('custom.to_date'),
                'col' => 'col-md-12'
            ),
//            'paginate' => array(
//                'type' => 'select',
//                'options' => paginationSelect(),
//                'multiple' => false,
//                'default' => '',
//                'label' => __('custom.filter_pagination'),
//                'value' => $request->input('paginate') ?? Publication::PAGINATE,
//                'col' => 'col-md-3'
//            ),
        );
    }
    private function newsFilters($request)
    {
        return array(
            'titleContent' => array(
                'type' => 'text',
                'label' => __('custom.title_content'),
                'value' => $request->input('titleContent'),
                'col' => 'col-md-4'
            ),
            'from' => array(
                'type' => 'datepicker',
                'value' => $request->input('from'),
                'label' => __('custom.from_date'),
                'col' => 'col-md-4'
            ),
            'to' => array(
                'type' => 'datepicker',
                'value' => $request->input('to'),
                'label' => __('custom.to_date'),
                'col' => 'col-md-4'
            ),
            'paginate' => array(
                'type' => 'select',
                'options' => paginationSelect(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.filter_pagination'),
                'value' => $request->input('paginate') ?? Publication::PAGINATE,
                'col' => 'col-md-3'
            ),
        );
    }

    private function boardSorters()
    {
        return array(
            'fieldOfAction' => ['class' => 'col-md-3', 'label' => trans_choice('custom.field_of_actions', 1)],
            'authority' => ['class' => 'col-md-2', 'label' => __('custom.type_of_governing')],
            'actOfCreation' => ['class' => 'col-md-2', 'label' => __('validation.attributes.act_of_creation')],
            'chairmanType' => ['class' => 'col-md-2', 'label' => __('validation.attributes.advisory_chairman_type_id')],
            'npo' => ['class' => 'col-md-2', 'label' => __('custom.npo')],
        );
    }

    private function boardFilters($request)
    {
        $fields = FieldOfAction::select('field_of_actions.*')
            ->advisoryBoard()
            ->with('translations')
            ->joinTranslation(FieldOfAction::class)
            ->whereLocale(app()->getLocale())
            ->orderBy('field_of_action_translations.name', 'asc')
            ->get();
        $field_of_actions = FieldOfAction::advisoryBoard()->with('translations')->orderBy('id')->get();
        $authority = AuthorityAdvisoryBoard::select('authority_advisory_board.*')
            ->with(['translation'])
            ->joinTranslation(AuthorityAdvisoryBoard::class)
            ->whereLocale(app()->getLocale())
            ->orderBy('authority_advisory_board_translations.name', 'asc')
            ->get();

        $act_of_creation = AdvisoryActType::select('advisory_act_type.*')
            ->with(['translation'])
            ->joinTranslation(AdvisoryActType::class)
            ->whereLocale(app()->getLocale())
            ->orderBy('advisory_act_type_translations.name', 'asc')
            ->get();

        $chairman_types = AdvisoryChairmanType::select('advisory_chairman_type.*')
            ->with(['translation'])
            ->joinTranslation(AdvisoryChairmanType::class)
            ->whereLocale(app()->getLocale())
            ->orderBy('advisory_chairman_type_translations.name', 'asc')
            ->get();

        return array(
            'keywords' => array(
                'type' => 'text',
                'label' => __('validation.attributes.name'),
                'value' => $request->input('keywords'),
                'col' => 'col-md-12'
            ),
            'fieldOfActions' => array(
                'type' => 'select',
                'options' => optionsFromModel($fields),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.field_of_actions', 1),
                'value' => $request->input('fieldOfActions'),
                'col' => 'col-md-6'
            ),
            'authoritys' => array(
                'type' => 'select',
                'options' => optionsFromModel($authority),
                'multiple' => true,
                'default' => '',
                'label' => __('custom.type_of_governing'),
                'value' => $request->input('authoritys'),
                'col' => 'col-md-6'
            ),
            'actOfCreations' => array(
                'type' => 'select',
                'options' => optionsFromModel($act_of_creation),
                'multiple' => true,
                'default' => '',
                'label' => __('validation.attributes.act_of_creation'),
                'value' => $request->input('actOfCreation'),
                'col' => 'col-md-6'
            ),
            'chairmanTypes' => array(
                'type' => 'select',
                'options' => optionsFromModel($chairman_types),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.advisory_chairman_type', 1),
                'value' => $request->input('chairmanTypes'),
                'col' => 'col-md-6'
            ),
            'npo' => array(
                'type' => 'select',
                'options' => array(
                    ['value' => 1, 'name' => __('custom.yes')],
                    ['value' => 0, 'name' => __('custom.no')],
                    ['value' => '', 'name' => __('custom.any')],
                ),
                'default' => '',
                'label' => __('custom.presence_npo_representative'),
                'value' => $request->input('npo'),
                'col' => 'col-md-6'
            ),
            'status' => array(
                'type' => 'select',
                'options' => optionsStatusesFilter(true, '-1', __('custom.any')),
                'default' => -1,
                'label' => __('custom.status'),
                'value' => $request->input('status'),
                'col' => 'col-md-6'
            ),
            'paginate' => array(
                'type' => 'select',
                'options' => paginationSelect(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.filter_pagination'),
                'value' => $request->input('paginate') ?? AdvisoryBoard::PAGINATE,
                'col' => 'col-md-s4'
            ),
        );
    }

    private function sortersReport()
    {
        return array(
            'fieldOfAction' => ['class' => 'col-md-3', 'label' => trans_choice('custom.field_of_actions', 1)],
            'authority' => ['class' => 'col-md-2', 'label' => __('custom.type_of_governing')],
            'actOfCreation' => ['class' => 'col-md-2', 'label' => __('validation.attributes.act_of_creation')],
            'chairmanType' => ['class' => 'col-md-2', 'label' => __('validation.attributes.advisory_chairman_type_id')],
            'npo' => ['class' => 'col-md-2', 'label' => __('custom.npo')],
        );
    }

    private function filtersReport($request, $currentRequest){
        $fields = FieldOfAction::select('field_of_actions.*')
            ->advisoryBoard()
            ->with('translations')
            ->joinTranslation(FieldOfAction::class)
            ->whereLocale(app()->getLocale())
            ->orderBy('field_of_action_translations.name', 'asc')
            ->get();
        $authority = AuthorityAdvisoryBoard::select('authority_advisory_board.*')
            ->with(['translation'])
            ->joinTranslation(AuthorityAdvisoryBoard::class)
            ->whereLocale(app()->getLocale())
            ->orderBy('authority_advisory_board_translations.name', 'asc')
            ->get();

        $act_of_creation = AdvisoryActType::select('advisory_act_type.*')
            ->with(['translation'])
            ->joinTranslation(AdvisoryActType::class)
            ->whereLocale(app()->getLocale())
            ->orderBy('advisory_act_type_translations.name', 'asc')
            ->get();

        return array(
            'fieldOfActions' => array(
                'type' => 'select',
                'options' => optionsFromModel($fields),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.field_of_actions', 1),
                'value' => $request->input('fieldOfActions'),
                'col' => 'col-md-6'
            ),
            'authoritys' => array(
                'type' => 'select',
                'options' => optionsFromModel($authority),
                'multiple' => true,
                'default' => '',
                'label' => __('custom.type_of_governing'),
                'value' => $request->input('authoritys'),
                'col' => 'col-md-6'
            ),
            'actOfCreations' => array(
                'type' => 'select',
                'options' => optionsFromModel($act_of_creation),
                'multiple' => true,
                'default' => '',
                'label' => __('validation.attributes.act_of_creation'),
                'value' => $request->input('actOfCreation'),
                'col' => 'col-md-6'
            ),
            'npo' => array(
                'type' => 'select',
                'options' => array(
                    ['value' => 1, 'name' => __('custom.yes')],
                    ['value' => 0, 'name' => __('custom.no')],
                    ['value' => '', 'name' => __('custom.any')],
                ),
                'default' => '',
                'label' => __('custom.presence_npo_representative'),
                'value' => $request->input('npo'),
                'col' => 'col-md-6'
            ),
            'meetingFrom' => array(
                'type' => 'datepicker',
                'value' => $request->input('meetingFrom'),
                'label' => 'Заседания в периода (от)',
                'col' => 'col-md-6'
            ),
            'meetingTo' => array(
                'type' => 'datepicker',
                'value' => $request->input('meetingTo'),
                'label' => 'Заседания в периода (до)',
                'col' => 'col-md-6'
            ),
            'status' => array(
                'type' => 'select',
                'label' => __('custom.status'),
                'multiple' => false,
                'options' => array(
                    ['name' => __('custom.all'), 'value' => ''],
                    ['name' => trans_choice('custom.active', 1), 'value' => 'active'],
                    ['name' => trans_choice('custom.inactive_m', 1), 'value' => 'inactive'],
                ),
                'value' => request()->input('status'),
                'default' => empty($currentRequest) ? 'active' :'',
                'col' => 'col-md-6'
            ),
            'paginate' => array(
                'type' => 'select',
                'options' => paginationSelect(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.filter_pagination'),
                'value' => $request->input('paginate') ?? config('app.default_paginate'),
                'col' => 'col-md-3'
            ),

        );
    }

    /**
     * @param $item
     * @param $extraItems
     * @return void
     */
    private function composeBreadcrumbs($item, $extraItems = []){
        $customBreadcrumbs = array(
            ['name' => trans_choice('custom.advisory_boards', 2), 'url' => route('advisory-boards.index')]
        );
        if($item && $item->policyArea){
            $customBreadcrumbs[] = ['name' => $item->policyArea->name, 'url' => route('advisory-boards.index').'?fieldOfActions[]='.$item->policyArea->id];
        }
        if($item){
            $customBreadcrumbs[] = ['name' => $item->name, 'url' => !empty($extraItems) ? route('advisory-boards.view', $item) : null];
        }
        if(!empty($extraItems)){
            foreach ($extraItems as $eItem){
                $customBreadcrumbs[] = $eItem;
            }
        }
        $this->setBreadcrumbsFull($customBreadcrumbs);
    }
}
