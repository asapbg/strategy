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
use App\Models\CustomRole;
use App\Models\FieldOfAction;
use App\Models\Page;
use App\Models\Publication;
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
//        $this->setSlider(trans_choice('custom.advisory_boards', 2), AdvisoryBoard::DEFAULT_HEADER_IMG);
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $customRequestParam = null;
//        if (!$request->ajax() && is_null($request->input('status'))) {
//            $request->request->add(['status' => 1]);
//            $customRequestParam = ['status' => 1];
//        }

        $rssUrl = config('feed.feeds.adv_boards.url');

        $groupOptions = array(
            ['value' => '', 'name' => ''],
            ['value' => 'fieldOfAction', 'name' => trans_choice('custom.field_of_actions', 1)],
            ['value' => 'authority', 'name' => __('custom.type_of_governing')],
            ['value' => 'chairmanType', 'name' => trans_choice('custom.advisory_chairman_type', 1)],
            ['value' => 'npo', 'name' => __('custom.presence_npo_representative')],
            ['value' => 'actOfCreation', 'name' => __('validation.attributes.act_of_creation')],
            ['value' => 'status', 'name' => __('validation.attributes.status')]
        );

        $rf = $request->all();
        $requestGroupBy = $rf['groupBy'] ?? null;

        $requestFilter = $request->all();
        //Filter
        $filter = $this->boardFilters($request);
        // Sorting the options by value
//            usort($filter['status']['options'], function ($a, $b) {
//                return $a['value'] <=> $b['value'];
//            });
//        if (!$request->ajax() && is_null($request->input('status'))) {
//            $filter['status']['value'] = 1;
//            $requestFilter['status'] = 1;
//        }
        //Sorter
        $sorter = $this->boardSorters();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'active';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');
        $queryDefaultSort = $request->filled('order_by') ? null : true;
        $paginate = $requestFilter['paginate'] ?? AdvisoryBoard::PAGINATE;

        $orderByName = !isset($requestFilter['status']) || $requestFilter['status'] == '';

        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        $pageTitle = $this->pageTitle;
//dd($requestGroupBy, $sort);
        $groupByColumn = ['advisory_boards.id', 'advisory_board_translations.name'];
//        if($requestGroupBy){
        if ($requestGroupBy == 'fieldOfAction' || $sort == 'fieldOfAction') {
            $groupByColumn[] = 'field_of_action_translations.name';
        }
        if ($requestGroupBy == 'authority' || $sort == 'authority') {
            $groupByColumn[] = 'authority_advisory_board_translations.name';
        }
        if ($requestGroupBy == 'chairmanType' || $sort == 'chairmanType') {
            $groupByColumn[] = 'advisory_chairman_type_translations.name';
        }
        if ($requestGroupBy == 'npo' || $sort == 'npo') {
            $groupByColumn[] = 'advisory_boards.has_npo_presence';
        }
        if ($requestGroupBy == 'actOfCreation' || $sort == 'actOfCreation') {
            $groupByColumn[] = 'advisory_act_type_translations.name';
        }
        if ($requestGroupBy == 'status') {
            $groupByColumn[] = 'advisory_boards.active';
        }
//        }

        $items = AdvisoryBoard::select('advisory_boards.*')
            ->with(['policyArea', 'policyArea.translations', 'translations', 'moderators',
                'authority', 'authority.translations', 'advisoryChairmanType', 'advisoryChairmanType.translations',
                'advisoryActType', 'advisoryActType.translations'])
            ->leftJoin('advisory_board_translations', function ($j) {
                $j->on('advisory_board_translations.advisory_board_id', '=', 'advisory_boards.id')
                    ->where('advisory_board_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('field_of_actions', 'field_of_actions.id', '=', 'advisory_boards.policy_area_id')
            ->leftJoin('field_of_action_translations', function ($j) {
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('authority_advisory_board', 'authority_advisory_board.id', '=', 'advisory_boards.authority_id')
            ->leftJoin('authority_advisory_board_translations', function ($j) {
                $j->on('authority_advisory_board_translations.authority_advisory_board_id', '=', 'authority_advisory_board.id')
                    ->where('authority_advisory_board_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('advisory_act_type', 'advisory_act_type.id', '=', 'advisory_boards.advisory_act_type_id')
            ->leftJoin('advisory_act_type_translations', function ($j) {
                $j->on('advisory_act_type_translations.advisory_act_type_id', '=', 'advisory_act_type.id')
                    ->where('advisory_act_type_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('advisory_chairman_type', 'advisory_chairman_type.id', '=', 'advisory_boards.advisory_chairman_type_id')
            ->leftJoin('advisory_chairman_type_translations', function ($j) {
                $j->on('advisory_chairman_type_translations.advisory_chairman_type_id', '=', 'advisory_chairman_type.id')
                    ->where('advisory_chairman_type_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('advisory_board_members', 'advisory_board_members.advisory_board_id', '=', 'advisory_boards.id')
            ->leftJoin('advisory_board_member_translations', function ($j) {
                $j->on('advisory_board_member_translations.advisory_board_member_id', '=', 'advisory_board_members.id')
                    ->where('advisory_board_member_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('advisory_board_npos', 'advisory_board_npos.advisory_board_id', '=', 'advisory_boards.id')
            ->leftJoin('advisory_board_npo_translations', function ($j) {
                $j->on('advisory_board_npo_translations.advisory_board_npo_id', '=', 'advisory_board_npos.id')
                    ->where('advisory_board_npo_translations.locale', '=', app()->getLocale());
            })
            ->where('advisory_boards.public', true)
            ->FilterBy($requestFilter)
            ->SortedBy($sort, $sortOrd)
            ->when($queryDefaultSort, function ($query) use ($groupByColumn) {
                $query->orderBy('advisory_boards.active', 'desc')
                    ->when(count($groupByColumn) < 3, fn($q) => $q->orderBy('advisory_board_translations.name'))
                    ->when(count($groupByColumn) == 3, fn($q) => $q->orderBy($groupByColumn[2], 'asc'));
            })
            ->groupBy($groupByColumn);

        if ($request->input('export_excel') || $request->input('export_as_excel') || $request->input('export_pdf') || $request->input('export_as_pdf')) {
            $items = $items->get();
            $exportData = [
                'title' => __('custom.adv_board_report_title'),
                'rows' => $items,
                'searchMeetings' => $groupByColumn
            ];

            $fileName = 'adv_report_' . Carbon::now()->format('Y_m_d_H_i_s');
            if ($request->input('export_pdf') || $request->input('export_as_pdf')) {
                ini_set('max_execution_time', 60);
                $pdf = PDF::loadView('exports.adv_report', ['data' => $exportData, 'isPdf' => true])->setPaper('a4', 'landscape');
                return $pdf->download($fileName . '.pdf');
            } else {
                return Excel::download(new AdvBoardReportExport($exportData), $fileName . '.xlsx');
            }
        } else {
            $items = $items->paginate($paginate);
        }

        $subscribeFilter = $requestFilter;
        if (isset($subscribeFilter['status'])) {
            $subscribeFilter['status'] = str($subscribeFilter['status']);
        }
        $hasSubscribeEmail = $this->hasSubscription(null, AdvisoryBoard::class, $subscribeFilter);
        $hasSubscribeRss = false;

        $closeSearchForm = true;
        if ($request->ajax()) {
            $closeSearchForm = false;
            return view('site.advisory-boards.list', compact('filter', 'sorter', 'items', 'rf', 'groupOptions', 'hasSubscribeEmail', 'hasSubscribeRss', 'requestFilter', 'rssUrl', 'closeSearchForm'));
        }
        $this->setSeo(__('site.seo_title'), trans_choice('custom.advisory_boards', 2), '', array('title' => __('site.seo_title'), 'description' => trans_choice('custom.advisory_boards', 2), 'img' => AdvisoryBoard::DEFAULT_IMG));
        return $this->view('site.advisory-boards.index', compact('filter', 'sorter', 'items', 'pageTitle', 'defaultOrderBy', 'defaultDirection', 'groupOptions', 'hasSubscribeEmail', 'hasSubscribeRss', 'requestFilter', 'rssUrl', 'closeSearchForm', 'customRequestParam'));
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
        $rssUrl = route('rss.adv_boards.item', $item->id);
        $item = AdvisoryBoard::where('id', $item->id)
            ->with(['customSections' => function ($query) {
                $query->with(['files', 'translations']);
            }, 'npos' => function ($query) {
                $query->with('translations');
            }, 'members' => function ($query) {
                $query->with(['translations', 'institution']);
            }, 'meetings' => function ($query) {
                $query->where('next_meeting', '>=', Carbon::now()->startOfYear())
                    ->with(['translations', 'siteFiles'])->orderBy('next_meeting', 'desc');
            }, 'secretariat' => function ($query) {
                $query->with(['translations', 'siteFiles']);
            }, 'workingProgram' => function ($query) {
                $query->with(['translations', 'siteFiles']);
            }, 'policyArea'])->first();

        $nextMeeting = AdvisoryBoardMeeting::where('advisory_board_id', $item->id)
            ->where('next_meeting', '>', Carbon::now())
            ->orderBy('next_meeting', 'asc')
            ->get()->first();

        $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();
        $pageTitle = $item->name;
        $this->title_singular = $item->name;
        if ($item->file_id > 0) {
            $this->setSlider($item->name, $item->headerImg);
        }

        $hasSubscribeEmail = $this->hasSubscription($item);
        $hasSubscribeRss = false;

        $this->composeBreadcrumbs($item, array(['name' => __('custom.main_information'), 'url' => '']));

        $this->setSeo($item->name, $item->ogDescription, '', array('title' => $item->name, 'description' => $item->ogDescription, 'img' => $item->mainImg ? $item->mainImg->path : AdvisoryBoard::DEFAULT_IMG));
        return $this->view('site.advisory-boards.view', compact('item', 'customSections', 'pageTitle', 'nextMeeting', 'hasSubscribeEmail', 'hasSubscribeRss', 'rssUrl'));
    }


    public function showSection(Request $request, AdvisoryBoard $item, $sectionId = 0)
    {
        $section = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->where('id', $sectionId)->first();
        $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();
        if (!$section) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $this->title_singular = $pageTitle = $item->name;
        if ($item->file_id > 0) {
            $this->setSlider($item->name, $item->headerImg);
        }
        $this->composeBreadcrumbs($item, array(['name' => $section->title, 'url' => '']));
        $this->setSeo($item->name, $item->ogDescription, '', array('title' => $item->name, 'description' => $item->ogDescription, 'img' => $item->mainImg ? $item->mainImg->path : AdvisoryBoard::DEFAULT_IMG));
        return $this->view('site.advisory-boards.view_section', compact('item', 'section', 'customSections', 'pageTitle'));
    }

    public function archiveMeetings(Request $request, AdvisoryBoard $item)
    {
        $requestFilter = $request->all();
        $paginate = $request->filled('paginate') ? $request->get('paginate') : AdvisoryBoard::PAGINATE;

        $itemsCalendar = array();
        $itemsCalendarDB = AdvisoryBoardMeeting::with(['translations'])->where('advisory_board_id', $item->id)->get();
        if ($itemsCalendarDB->count()) {
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
        if (!isset($requestFilter['to'])) {
            $requestFilter['to'] = Carbon::now()->startOfYear();
        }
        $requestFilter['criteria'] = $requestFilter['criteria'] ?? AdvisoryBoardMeeting::FILTER_ALL;

        $filter = $this->archiveFilters($request);
        $pageTitle = $item->name;

        if ($item->file_id > 0) {
            $this->setSlider($item->name, $item->headerImg);
        }

        $items = AdvisoryBoardMeeting::with(['translations'])
            ->where('advisory_board_id', $item->id)
//            ->where('next_meeting', '<', Carbon::now()->startOfYear()->format('Y-m-d H:i:s'))
            ->orderBy('next_meeting', 'desc')
            ->when($requestFilter['criteria'] == AdvisoryBoardMeeting::FILTER_CURRENT_YEAR, fn($q) => $q->where('next_meeting', '>=', Carbon::now()->startOfYear())->where('next_meeting', '<=', Carbon::now()->endOfYear()))
            ->when($requestFilter['criteria'] == AdvisoryBoardMeeting::FILTER_SPECIFIC_YEAR, fn($q) => $q->where('next_meeting', '>=', Carbon::createFromFormat('Y', $requestFilter['year'])->startOfYear())->where('next_meeting', '<=', Carbon::createFromFormat('Y', $requestFilter['year'])->endOfYear()))
            ->when($requestFilter['criteria'] == AdvisoryBoardMeeting::FILTER_PERIOD, fn($q) => $q->where('next_meeting', '>=', Carbon::parse($requestFilter['from'])->startOfDay())->where('next_meeting', '<=', Carbon::parse($requestFilter['to'])->endOfDay()))
//            ->FilterBy($requestFilter)
            ->get();
//            ->paginate($paginate);

        $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();
        if ($request->ajax()) {
            return view('site.advisory-boards.archive_meeting_list', compact('filter', 'items', 'item', 'itemsCalendar'));
        }

        $this->composeBreadcrumbs($item, array(['name' => __('custom.archive') . ' ' . __('custom.meetings_and_decisions'), 'url' => '']));
        $this->setSeo($item->name, $item->ogDescription, '', array('title' => $item->name, 'description' => $item->ogDescription, 'img' => $item->mainImg ? $item->mainImg->path : AdvisoryBoard::DEFAULT_IMG));
        return $this->view('site.advisory-boards.archive_meeting', compact('filter', 'items', 'pageTitle', 'item', 'customSections', 'itemsCalendar'));
    }

    public function archiveWorkPrograms(Request $request, AdvisoryBoard $item)
    {
        $requestFilter = $request->all();
        $paginate = $request->filled('paginate') ? $request->get('paginate') : AdvisoryBoard::PAGINATE;

        if (!isset($requestFilter['to'])) {
            $requestFilter['to'] = Carbon::now()->startOfYear();
        }
        $filter = $this->archiveFilters($request);
        $pageTitle = $item->name;

        if ($item->file_id > 0) {
            $this->setSlider($item->name, $item->headerImg);
        }

        $items = AdvisoryBoardFunction::with(['translations'])
            ->where('advisory_board_id', $item->id)
//            ->with(['translations', 'siteFiles', 'siteFiles.versions'])
            ->where('working_year', '<', Carbon::now()->startOfYear()->format('Y-m-d H:i:s'))
            ->FilterBy($requestFilter)
            ->orderBy('working_year', 'desc')
            ->paginate($paginate);
        $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();

        if ($request->ajax()) {
            return view('site.advisory-boards.archive_wotk_programs_list', compact('filter', 'items', 'item'));
        }
        $this->composeBreadcrumbs($item, array(['name' => __('custom.archive') . ' ' . __('custom.work_programs'), 'url' => '']));
        $this->setSeo($item->name, $item->ogDescription, '', array('title' => $item->name, 'description' => $item->ogDescription, 'img' => $item->mainImg ? $item->mainImg->path : AdvisoryBoard::DEFAULT_IMG));

        return $this->view('site.advisory-boards.archive_work_programs', compact('filter', 'items', 'pageTitle', 'item', 'customSections'));
    }

    public function itemNews(Request $request, AdvisoryBoard $item)
    {
        $news = Publication::select('publication.*')
            ->ActivePublic()
            ->with(['translations', 'category', 'category.translations', 'mainImg'])
            ->leftJoin('publication_translations', function ($j) {
                $j->on('publication_translations.publication_id', '=', 'publication.id')
                    ->where('publication_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('field_of_actions', 'field_of_actions.id', '=', 'publication.publication_category_id')
            ->leftJoin('field_of_action_translations', function ($j) {
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->where(function ($q) use ($item) {
                $q->where('publication.advisory_boards_id', $item->id)
                    ->orWhereIn('is_adv_board_user', ($item->moderators->count() ? $item->moderators->pluck('id')->toArray() : []));
            })
            ->orderBy('published_at', 'desc')
            ->GroupBy('publication.id', 'publication_translations.id', 'field_of_action_translations.id')
            ->get();

        $pageTitle = $item->name;

        if ($item->file_id > 0) {
            $this->setSlider($item->name, $item->headerImg);
        }

        $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();
        $this->composeBreadcrumbs($item, array(['name' => trans_choice('custom.news', 2), 'url' => '']));
        $this->setSeo($item->name, $item->ogDescription, '', array('title' => $item->name, 'description' => $item->ogDescription, 'img' => $item->mainImg ? $item->mainImg->path : AdvisoryBoard::DEFAULT_IMG));

        return $this->view('site.advisory-boards.view_news', compact('item', 'news', 'pageTitle', 'customSections'));
    }

    public function itemNewsDetails(Request $request, AdvisoryBoard $item, Publication $news)
    {
        $pageTitle = $item->name;
        $publication = $news;
        if ($item->file_id > 0) {
            $this->setSlider($item->name, $item->headerImg);
        }
        $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();
        $this->composeBreadcrumbs($item, array(
            ['name' => trans_choice('custom.news', 2), 'url' => route('advisory-boards.view.news', $item)],
            ['name' => $news->title, 'url' => '']
        ));
        $this->setSeo($news->meta_title ?? $news->title, $news->meta_description ?? $news->short_content, $news->meta_keyword, array('title' => $news->meta_title ?? $news->title, 'img' => $news->mainImg ? $news->mainImg->path : Publication::DEFAULT_IMG_ADV));

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
     * @param AdvisoryBoard $advisoryBoard
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
        if ($itemId) {
            $item = AdvisoryBoard::with(['translations', 'moderators'])->find($itemId);
//            $item = AdvisoryBoard::with(['translations', 'moderators', 'moderators.user', 'moderatorInformation', 'moderatorInformation.translations', 'moderatorInformation.files'])->find($itemId);
            if (!$item) {
                abort(404);
            }
            $pageTitle = $item->name;

            if ($item->file_id > 0) {
                $this->setSlider($item->name, $item->headerImg);
            }

            $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();
            $this->composeBreadcrumbs($item, array(['name' => trans_choice('custom.contacts', 2), 'url' => '']));
            $this->setSeo($item->name . ' | ' . trans_choice('custom.contacts', 2), $item->ogDescription, '', array('title' => $item->name . ' | ' . trans_choice('custom.contacts', 2), 'description' => $item->ogDescription, 'img' => $item->mainImg ? $item->mainImg->path : AdvisoryBoard::DEFAULT_IMG));
            return $this->view('site.advisory-boards.contacts_inner', compact('pageTitle', 'item', 'customSections'));
        } else {
            $pageTitle = $this->pageTitle;
            $moderators = User::role([CustomRole::MODERATOR_ADVISORY_BOARDS, CustomRole::MODERATOR_ADVISORY_BOARD])
                ->whereNotIn('email', User::EXCLUDE_CONTACT_USER_BY_MAIL)->get();
            $this->setSeo(__('site.seo_title') . ' - ' . trans_choice('custom.advisory_boards', 2), trans_choice('custom.contacts', 2), '', array('title' => __('site.seo_title') . ' - ' . trans_choice('custom.advisory_boards', 2), 'description' => trans_choice('custom.contacts', 2), 'img' => AdvisoryBoard::DEFAULT_IMG));
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
            ->leftJoin('publication_translations', function ($j) {
                $j->on('publication_translations.publication_id', '=', 'publication.id')
                    ->where('publication_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('field_of_actions', 'field_of_actions.id', '=', 'publication.publication_category_id')
            ->leftJoin('field_of_action_translations', function ($j) {
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->FilterBy($requestFilter)
            ->where('publication.type', PublicationTypesEnum::TYPE_ADVISORY_BOARD->value)
//            ->where(function ($q){
//                $q->whereNotNull('publication.advisory_boards_id')
//                    ->orWhere('is_adv_board_user', 1);
//            })
            ->SortedBy($sort, $sortOrd)
            ->GroupBy('publication.id', 'publication_translations.id', 'field_of_action_translations.id')
            ->paginate($paginate);

        if ($request->ajax()) {
            return view('site.advisory-boards.main_news_list', compact('filter', 'sorter', 'items', 'requestFilter'));
        }

        $this->setSeo(__('site.seo_title') . ' - ' . trans_choice('custom.advisory_boards', 2), trans_choice('custom.news', 2), '', array('title' => __('site.seo_title') . ' - ' . trans_choice('custom.advisory_boards', 2), 'description' => trans_choice('custom.news', 2), 'img' => AdvisoryBoard::DEFAULT_IMG));
        return $this->view('site.advisory-boards.main_news', compact('filter', 'sorter', 'items', 'defaultOrderBy', 'defaultDirection', 'pageTitle', 'requestFilter'));
    }

    public function newsDetails(Request $request, Publication $item)
    {
        $pageTitle = trans_choice('custom.advisory_boards', 2);
//        $this->setSeo($item->meta_title, $item->meta_description, $item->meta_keyword);
        $this->setSeo($item->meta_title ?? $item->title, $item->meta_description ?? $item->short_content, $item->meta_keyword, array('title' => $item->meta_title ?? $item->title, 'img' => Page::DEFAULT_IMG));

        $publication = $item;
//        $this->setSlider(trans_choice('custom.advisory_boards', 2), $item->headerImg);
        $this->setSeo($publication->meta_title ?? $publication->title, $publication->meta_description ?? $publication->short_content, $publication->meta_keyword, array('title' => $publication->meta_title ?? $publication->title, 'img' => $publication->mainImg ? $publication->mainImg->path : Publication::DEFAULT_IMG_ADV));
        return $this->view('site.advisory-boards.main_news_details', compact('publication', 'pageTitle'));
    }

    public function documents()
    {
        $page = Page::with(['files' => function ($q) {
            $q->where('locale', '=', app()->getLocale());
        }])
            ->where('system_name', '=', Page::ADV_BOARD_DOCUMENTS)
            ->first();
        if (!$page) {
            abort(404);
        }
        $pageTitle = $page->name;
//        $this->setSeo($page->meta_title, $page->meta_description, $page->meta_keyword);
        $this->setSeo($page->meta_title ?? $page->name, $page->meta_description ?? $page->short_content, $page->meta_keyword, array('title' => $page->meta_title ?? $page->name, 'img' => Page::DEFAULT_IMG));

        return $this->view('site.advisory-boards.page', compact('page', 'pageTitle'));
    }

    public function info()
    {
        $page = Page::with(['files' => function ($q) {
            $q->where('locale', '=', app()->getLocale());
        }])
            ->where('system_name', '=', Page::ADV_BOARD_INFO)
            ->first();
        if (!$page) {
            abort(404);
        }
        $pageTitle = $page->name;
//        $this->setSeo($page->meta_title, $page->meta_description, $page->meta_keyword);
        $this->setSeo($page->meta_title ?? $page->name, $page->meta_description ?? $page->short_content, $page->meta_keyword, array('title' => $page->meta_title ?? $page->name, 'img' => Page::DEFAULT_IMG));

        return $this->view('site.advisory-boards.page', compact('page', 'pageTitle'));
    }

    public function reports(Request $request)
    {
        //Filter
        $rf = $request->all();
//        $requestGroupBy = $rf['groupBy'] ?? null;
        //Filter
        $requestFilter = $request->all();
        if (empty($rf)) {
            $requestFilter['status'] = 'active';
        }
        $filter = $this->filtersReport($request, $rf);
        //Sorter
        $sorter = $this->sortersReport();
//        $sort = $request->filled('order_by') ? $request->input('order_by') : 'status';
        $sort = $request->filled('order_by') ? $request->input('order_by') : ($request->filled('export_sort_by') ? $request->input('export_sort_by') : 'status');
//        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');
        $sortOrd = $request->filled('direction') ? $request->input('direction') : ($request->filled('export_sort_direction') ? $request->input('export_sort_direction') : (!$request->filled('order_by') ? 'desc' : 'asc'));
        $queryDefaultSort = $request->filled('order_by') ? null : true;

        $paginate = $requestFilter['paginate'] ?? config('app.default_paginate');
        $defaultOrderBy = $sort;
        $defaultDirection = $sortOrd;

        $selectColumns = ['advisory_boards.*'];
        $searchMeetings = (isset($requestFilter['meetingFrom']) && !empty($requestFilter['meetingFrom'])) || (isset($requestFilter['meetingTo']) && !empty($requestFilter['meetingTo']));
        if ($searchMeetings) {
            $selectColumns[] = DB::raw('count(advisory_board_meetings.id) as meetings');
        }
        $q = AdvisoryBoard::select($selectColumns)
            ->with(['policyArea', 'policyArea.translations', 'translations', 'moderators',
                'authority', 'authority.translations', 'advisoryChairmanType', 'advisoryChairmanType.translations',
                'advisoryActType', 'advisoryActType.translations'])
            ->leftJoin('advisory_board_translations', function ($j) {
                $j->on('advisory_board_translations.advisory_board_id', '=', 'advisory_boards.id')
                    ->where('advisory_board_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('field_of_actions', 'field_of_actions.id', '=', 'advisory_boards.policy_area_id')
            ->leftJoin('field_of_action_translations', function ($j) {
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('authority_advisory_board', 'authority_advisory_board.id', '=', 'advisory_boards.authority_id')
            ->leftJoin('authority_advisory_board_translations', function ($j) {
                $j->on('authority_advisory_board_translations.authority_advisory_board_id', '=', 'authority_advisory_board.id')
                    ->where('authority_advisory_board_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('advisory_act_type', 'advisory_act_type.id', '=', 'advisory_boards.advisory_act_type_id')
            ->leftJoin('advisory_act_type_translations', function ($j) {
                $j->on('advisory_act_type_translations.advisory_act_type_id', '=', 'advisory_act_type.id')
                    ->where('advisory_act_type_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('advisory_chairman_type', 'advisory_chairman_type.id', '=', 'advisory_boards.advisory_chairman_type_id')
            ->leftJoin('advisory_chairman_type_translations', function ($j) {
                $j->on('advisory_chairman_type_translations.advisory_chairman_type_id', '=', 'advisory_chairman_type.id')
                    ->where('advisory_chairman_type_translations.locale', '=', app()->getLocale());
            });

        if ($searchMeetings) {
            $q->leftJoin('advisory_board_meetings', function ($j) {
                $j->on('advisory_board_meetings.advisory_board_id', '=', 'advisory_boards.id')
                    ->whereNull('advisory_board_meetings.deleted_at');
            });
        }

        $q->where('public', true)
            ->FilterBy($requestFilter)
//            ->when($requestGroupBy, function ($query) use($requestGroupBy){
//                if($requestGroupBy == 'fieldOfAction') {
//                    return $query->orderBy('field_of_action_translations.name');
//                } elseif($requestGroupBy == 'authority') {
//                    return $query->orderBy('authority_advisory_board_translations.name');
//                } elseif($requestGroupBy == 'chairmanType') {
//                    return $query->orderBy('advisory_chairman_type_translations.name');
//                } elseif($requestGroupBy == 'npo') {
//                    return $query->orderBy('advisory_boards.has_npo_presence');
//                } elseif($requestGroupBy == 'actOfCreation') {
//                    return $query->orderBy('advisory_act_type_translations.name');
//                }
//            })
            ->SortedBy($sort, $sortOrd)
            ->when($queryDefaultSort, function ($query) {
                $query->orderBy('advisory_boards.active', 'desc')
                    ->orderBy('advisory_board_translations.name', 'asc');
            });

        if ($searchMeetings) {
            $q->groupBy('advisory_boards.id', 'advisory_board_translations.name');
        }

        if ($request->input('export_excel') || $request->input('export_as_excel') || $request->input('export_pdf') || $request->input('export_as_pdf')) {
            $items = $q->get();
            $exportData = [
                'title' => __('custom.adv_board_report_title'),
                'rows' => $items,
                'searchMeetings' => $searchMeetings
            ];

            $fileName = 'adv_report_' . Carbon::now()->format('Y_m_d_H_i_s');
            if ($request->input('export_pdf') || $request->input('export_as_pdf')) {
                ini_set('max_execution_time', 60);
                $pdf = PDF::loadView('exports.adv_report', ['data' => $exportData, 'isPdf' => true])->setPaper('a4', 'landscape');
                return $pdf->download($fileName . '.pdf');
            } else {
                return Excel::download(new AdvBoardReportExport($exportData), $fileName . '.xlsx');
            }
        } else {
            $items = $q->paginate($paginate);
        }

        $closeSearchForm = false;
        if ($request->ajax()) {
            $closeSearchForm = false;
            return view('site.advisory-boards.list_report', compact('filter', 'sorter', 'items', 'rf', 'searchMeetings', 'closeSearchForm'));
        }

        $pageTitle = trans('custom.adv_board_report_title');
        $this->composeBreadcrumbs(null, array(['name' => __('custom.adv_board_report_title'), 'url' => '']));
        $this->setSeo(__('site.seo_title') . ' - ' . trans_choice('custom.advisory_boards', 2), trans_choice('custom.reports', 2), '', array('title' => __('site.seo_title') . ' - ' . trans_choice('custom.advisory_boards', 2), 'description' => trans_choice('custom.reports', 2), 'img' => AdvisoryBoard::DEFAULT_IMG));

        return $this->view('site.advisory-boards.report', compact('filter', 'sorter', 'items', 'pageTitle', 'rf', 'defaultOrderBy', 'defaultDirection', 'searchMeetings', 'closeSearchForm'));
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
            'criteria' => array(
                'type' => 'select',
                'options' => AdvisoryBoardMeeting::getCriterias(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.criteria'),
                'value' => $request->input('criteria') ?? 'all',
                'col' => 'col-md-12'
            ),
            'year' => array(
                'type' => 'select',
                'options' => AdvisoryBoardMeeting::getYearsRange(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.year'),
                'value' => $request->input('year') ?? date('Y'),
                'col' => 'col-md-12'
            ),
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
        return array(
            'keywords' => array(
                'type' => 'text',
                'label' => __('validation.attributes.name'),
                'value' => $request->input('keywords'),
                'col' => 'col-md-12'
            ),
            'fieldOfActions' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList(false, FieldOfAction::CATEGORY_NATIONAL)),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.field_of_actions', 1),
                'value' => $request->input('fieldOfActions'),
                'col' => 'col-md-6'
            ),
            'authoritys' => array(
                'type' => 'select',
                'options' => optionsFromModel(AuthorityAdvisoryBoard::optionsList()),
                'multiple' => true,
                'default' => '',
                'label' => __('custom.type_of_governing'),
                'value' => request()->input('authoritys'),
                'col' => 'col-md-6'
            ),
            'actOfCreations' => array(
                'type' => 'select',
                'options' => optionsFromModel(AdvisoryActType::optionsList()),
                'multiple' => true,
                'default' => '',
                'label' => __('validation.attributes.act_of_creation'),
                'value' => $request->input('actOfCreation'),
                'col' => 'col-md-6'
            ),
            'chairmanTypes' => array(
                'type' => 'select',
                'options' => optionsFromModel(AdvisoryChairmanType::optionsList()),
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
            'personName' => array(
                'type' => 'text',
                'label' => __('custom.adv_board_search_person'),
                'value' => $request->input('personName'),
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

    private function filtersReport($request, $currentRequest)
    {
        return array(
            'fieldOfActions' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList(false, FieldOfAction::CATEGORY_NATIONAL)),
                'multiple' => true,
                'default' => '',
                'label' => trans_choice('custom.field_of_actions', 1),
                'value' => $request->input('fieldOfActions'),
                'col' => 'col-md-6'
            ),
            'authoritys' => array(
                'type' => 'select',
                'options' => optionsFromModel(AuthorityAdvisoryBoard::optionsList()),
                'multiple' => true,
                'default' => '',
                'label' => __('custom.type_of_governing'),
                'value' => $request->input('authoritys'),
                'col' => 'col-md-6'
            ),
            'actOfCreations' => array(
                'type' => 'select',
                'options' => optionsFromModel(AdvisoryActType::optionsList()),
                'multiple' => true,
                'default' => '',
                'label' => __('validation.attributes.act_of_creation'),
                'value' => $request->input('actOfCreation'),
                'col' => 'col-md-6'
            ),
            'advisoryChairman' => array(
                'type' => 'select',
                'options' => optionsFromModel(AdvisoryChairmanType::optionsList()),
                'multiple' => true,
                'default' => '',
                'label' => __('validation.attributes.advisory_chairman_type_id'),
                'value' => $request->input('advisoryChairman'),
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
                'default' => empty($currentRequest) ? 'active' : '',
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
    private function composeBreadcrumbs($item, $extraItems = [])
    {
        $customBreadcrumbs = array(
            ['name' => trans_choice('custom.advisory_boards', 2), 'url' => route('advisory-boards.index')]
        );
        if ($item && $item->policyArea) {
            $customBreadcrumbs[] = ['name' => $item->policyArea->name, 'url' => route('advisory-boards.index') . '?fieldOfActions[]=' . $item->policyArea->id];
        }
        if ($item) {
            $customBreadcrumbs[] = ['name' => $item->name, 'url' => !empty($extraItems) ? route('advisory-boards.view', $item) : null];
        }
        if (!empty($extraItems)) {
            foreach ($extraItems as $eItem) {
                $customBreadcrumbs[] = $eItem;
            }
        }
        $this->setBreadcrumbsFull($customBreadcrumbs);
    }
}
