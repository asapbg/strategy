<?php

namespace App\Http\Controllers;

use App\Enums\PublicationTypesEnum;
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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
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
        $pageTitle = $this->pageTitle;
        $field_of_actions = FieldOfAction::advisoryBoard()->select('field_of_actions.*')
            ->whereLocale(app()->getLocale())
            ->joinTranslation(FieldOfAction::class)
            ->with(['translation'])
            ->orderBy('name')
            ->get();
        $authority_advisory_boards = AuthorityAdvisoryBoard::select('authority_advisory_board.*')
            ->whereLocale(app()->getLocale())
            ->joinTranslation(AuthorityAdvisoryBoard::class)
            ->with(['translation'])
            ->orderBy('name')
            ->get();
        $advisory_act_types = AdvisoryActType::select('advisory_act_type.*')
            ->whereLocale(app()->getLocale())
            ->joinTranslation(AdvisoryActType::class)
            ->with(['translation'])
            ->orderBy('name')
            ->get();
        $advisory_chairman_types = AdvisoryChairmanType::select('advisory_chairman_type.*')
            ->whereLocale(app()->getLocale())
            ->joinTranslation(AdvisoryChairmanType::class)
            ->with(['translation'])
            ->orderBy('name')
            ->get();
//        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_ADVISORY_BOARDS . '_' . app()->getLocale())->first();
        $pageTopContent = '';
        $status = request()->offsetGet('status');

        $is_search = $request->has('search');
        $filter_field_of_action = $request->get('filter_field_of_action');
        $filter_authority = $request->get('filter_authority');
        $filter_act_of_creation = $request->get('filter_act_of_creation');

        $filter_chairman_type = $request->get('filter_chairman_type');
        $keywords = $request->get('keywords');
        $npo= $request->get('npo');

        $sort = ($request->offsetGet('sort'))
            ? $request->offsetGet('sort')
            : "DESC";
        $order_by = ($request->offsetGet('order_by'))
            ? $request->offsetGet('order_by')
            : "name";
        $sort_table = (in_array($order_by, AdvisoryBoard::TRANSLATABLE_FIELDS))
            ? "advisory_board_translations"
            : "advisory_boards";
        $paginate = $request->filled('paginate') ? $request->get('paginate') : 50;

        $advisory_boards = AdvisoryBoard::select('advisory_boards.*')
            ->whereLocale(app()->getLocale())
            ->joinTranslation(AdvisoryBoard::class)
            ->with(['policyArea', 'translations', 'moderators'])
            ->when($keywords, function ($query) use ($keywords) {
                $query->where('name', 'ilike', '%' . $keywords . '%');
            })
            ->when($filter_field_of_action, function ($query) use ($filter_field_of_action) {
                $query->whereIn('policy_area_id', array_map('intval', $filter_field_of_action));
            })
            ->when($filter_authority, function ($query) use ($filter_authority) {
                $query->whereIn('authority_id', array_map('intval', $filter_authority));
            })
            ->when($filter_act_of_creation, function ($query) use ($filter_act_of_creation) {
                $query->whereIn('advisory_act_type_id', array_map('intval', $filter_act_of_creation));
            })
            ->when($filter_chairman_type, function ($query) use ($filter_chairman_type) {
                $query->whereIn('advisory_chairman_type_id', array_map('intval', $filter_chairman_type));
            })
            ->when(!is_null($status), function ($query) use ($status) {
                $query->where('active', (bool)$status);
            })
            ->when(!is_null($npo), function ($query) use ($npo) {
                $query->where('has_npo_presence', (bool)$npo);
            })
            ->where('public', true)
            ->orderBy('active', 'desc')
            ->orderBy("$sort_table.$order_by", $sort)
            ->paginate($paginate);

        if ($is_search) {
            return $this->view('site.advisory-boards.ajax-results', compact('advisory_boards'));
        }

        return $this->view('site.advisory-boards.index', compact(
            'pageTitle',
            'field_of_actions',
            'authority_advisory_boards',
            'advisory_act_types',
            'advisory_chairman_types',
            'pageTopContent',
            'advisory_boards',
        ));
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
                    ->with(['translations', 'siteFiles'])->orderBy('next_meeting', 'asc');
            }, 'secretariat' => function($query) {
                $query->with(['translations', 'siteFiles']);
            }, 'workingProgram' => function($query) {
                $query->with(['translations', 'siteFiles']);
        }])->first();

        $nextMeeting = AdvisoryBoardMeeting::where('advisory_board_id', $item->id)
            ->where('next_meeting' ,'>', Carbon::now())
            ->orderBy('next_meeting', 'asc')
            ->get()->first();

        $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();
        $pageTitle = $item->name;
        $this->title_singular = $item->name;
        $this->setSlider($item->name, $item->headerImg);
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
        $pageTitle = $this->pageTitle;
        $this->setSlider($item->name, $item->headerImg);
        $items = AdvisoryBoardMeeting::with(['translations'])
            ->where('advisory_board_id', $item->id)
            ->where('next_meeting', '<', Carbon::now()->startOfYear()->format('Y-m-d H:i:s'))
            ->orderBy('next_meeting', 'desc')
            ->FilterBy($requestFilter)
            ->paginate($paginate);

        $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();
        if( $request->ajax() ) {
            return view('site.advisory-boards.archive_meeting_list', compact('filter','items', 'item', 'itemsCalendar'));
        }

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
        $pageTitle = $this->pageTitle;
        $this->setSlider($item->name, $item->headerImg);
        $items = AdvisoryBoardFunction::with(['translations', 'siteFiles', 'siteFiles.versions'])
            ->where('advisory_board_id', $item->id)
            ->with(['translations', 'siteFiles'])
            ->where('working_year', '<', Carbon::now()->startOfYear()->format('Y-m-d H:i:s'))
            ->FilterBy($requestFilter)
            ->orderBy('working_year', 'desc')
            ->paginate($paginate);
        $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();

        if( $request->ajax() ) {
            return view('site.advisory-boards.archive_wotk_programs_list', compact('filter','items', 'item'));
        }

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
        return $this->view('site.advisory-boards.view_news', compact('item', 'news', 'pageTitle', 'customSections'));
    }

    public function itemNewsDetails(Request $request, AdvisoryBoard $item, Publication $news){
        $pageTitle = $news->title;
        $this->setSeo($news->meta_title, $news->meta_description, $news->meta_keyword);
        $publication = $news;
        $this->setSlider($item->name, $item->headerImg);
        $customSections = AdvisoryBoardCustom::with(['translations'])->where('advisory_board_id', $item->id)->orderBy('order', 'asc')->get()->pluck('title', 'id')->toArray();
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
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'published_at';
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
        $pageTitle = $item->title;
        $this->setSeo($item->meta_title, $item->meta_description, $item->meta_keyword);
        $publication = $item;
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
    private function newsFilters($request)
    {
        return array(
            'titleContent' => array(
                'type' => 'text',
                'label' => __('custom.title_content'),
                'value' => $request->input('title_content'),
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
}
