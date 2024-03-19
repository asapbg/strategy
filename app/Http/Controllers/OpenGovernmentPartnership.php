<?php

namespace App\Http\Controllers;

use App\Enums\OgpStatusEnum;
use App\Enums\PageModulesEnum;
use App\Enums\PublicationTypesEnum;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMeeting;
use App\Models\CustomRole;
use App\Models\OgpPlan;
use App\Models\Page;
use App\Models\Publication;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OpenGovernmentPartnership extends Controller
{
    private $pageTitle;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->title_singular = __('custom.open_government_partnership');
        $this->pageTitle = __('custom.open_government_partnership');
    }
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $pageTitle = $this->pageTitle;
        $this->composeBreadcrumbs();
        return $this->view('site.ogp.index', compact('pageTitle'));

    }

    public function info()
    {
        $page = Page::with(['files' => function($q) {
            $q->where('locale', '=', app()->getLocale());
        }])
            ->where('system_name', '=', Page::OGP_INFO)
            ->first();
        if(!$page){
            abort(404);
        }
        $pageTitle = $this->pageTitle;
        $this->setSeo($page->meta_title, $page->meta_description, $page->meta_keyword);
        $this->composeBreadcrumbs(null, array(['name' => $page->name, 'url' => '']));
        return $this->view('site.ogp.page', compact('page', 'pageTitle'));
    }

    public function contacts(Request $request, $itemId = null)
    {
        $pageTitle = $this->pageTitle;
        $moderators = User::role([CustomRole::MODERATOR_PARTNERSHIP])->get();
        $this->composeBreadcrumbs(null, array(['name' => trans_choice('custom.contacts', 2), 'url' => '']));
        return $this->view('site.ogp.contacts', compact('moderators', 'pageTitle'));
    }

    public function libraryView(Request $request, $slug = ''){
        $page = Page::with(['files' => function($q) {
            $q->where('locale', '=', app()->getLocale());
        }])
            ->where('slug', '=', $slug)
            ->first();

        if(!$page){
            return back()->with('warning', __('custom.record_not_found'));
        }
        $pageTitle = $this->pageTitle;
        $this->setSeo($page->meta_title, $page->meta_description, $page->meta_keyword);
        $this->composeBreadcrumbs(null, array(
            ['name' => __('custom.library'), 'url' => ''],
            ['name' => $page->name, 'url' => '']
        ));

        $library = Page::with(['translations'])
            ->where('module_enum', '=', PageModulesEnum::MODULE_OGP->value)
            ->orderBy('order_idx', 'asc')
            ->get();
        return $this->view('site.ogp.page', compact('page', 'pageTitle', 'library'));
    }

    public function news(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->newsFilters($request, $requestFilter);
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
            ->with(['translations',])
            ->leftJoin('publication_translations', function ($j){
                $j->on('publication_translations.publication_id', '=', 'publication.id')
                    ->where('publication_translations.locale', '=', app()->getLocale());
            })
            ->FilterBy($requestFilter)
            ->where('publication.type', PublicationTypesEnum::TYPE_OGP_NEWS->value)
            ->SortedBy($sort,$sortOrd)
            ->GroupBy('publication.id', 'publication_translations.id')
            ->paginate($paginate);

        if( $request->ajax() ) {
            return view('site.ogp.main_news_list', compact('filter','sorter', 'items'));
        }

        $this->composeBreadcrumbs(null, array(['name' => trans_choice('custom.news', 2), 'url' => '']));
        return $this->view('site.ogp.main_news', compact('filter','sorter', 'items', 'defaultOrderBy', 'defaultDirection', 'pageTitle'));
    }

    public function newsDetails(Request $request, Publication $item){
        $pageTitle = $this->pageTitle;
        $this->setSeo($item->meta_title, $item->meta_description, $item->meta_keyword);
        $publication = $item;
        $this->composeBreadcrumbs(null, array(
            ['name' => trans_choice('custom.news', 2), 'url' => route('ogp.news')],
            ['name' => $item->title, 'url' => '']
        ));
        return $this->view('site.ogp.main_news_details', compact('publication', 'pageTitle'));
    }

    public function events(Request $request)
    {
        $itemsCalendar = array();
        $advBoardId = Setting::where('name', '=', Setting::OGP_ADV_BOARD_FORUM)->first();
        $ogpPlans = OgpPlan::select('ogp_plan.*')
            ->Active()
            ->join('ogp_status', 'ogp_plan.ogp_status_id', '=', 'ogp_status.id')
            ->leftJoin('ogp_plan_translations', function ($j){
                $j->on('ogp_plan_translations.ogp_plan_id', '=', 'ogp_plan.id')
                    ->where('ogp_plan_translations.locale', '=', app()->getLocale());
            })
            ->whereIn('ogp_status.type', [OgpStatusEnum::IN_DEVELOPMENT->value, OgpStatusEnum::FINAL->value])
            ->orderBy('ogp_plan.created_at', 'desc')
            ->get();

        $devPlansUnions = '';
        if($ogpPlans->count()){
            foreach ($ogpPlans as $devPlan){
                $devPlansUnions.= 'union all
                            select
                                ogp_plan_schedule.id as id,
                                ogp_plan_schedule.ogp_plan_id as url_id,
                                \'ogp_plan\' as url_type,
                                ogp_plan_schedule_translations.name as title,
                                ogp_plan_schedule_translations.description as description,
                                case when ogp_plan_schedule.start_date is not null then ogp_plan_schedule.start_date::text else null end as start,
                                case when ogp_plan_schedule.end_date is not null then ogp_plan_schedule.end_date::text else null end as end
                            from ogp_plan_schedule
                            left join ogp_plan_schedule_translations on ogp_plan_schedule_translations.ogp_plan_schedule_id = ogp_plan_schedule.id and ogp_plan_schedule_translations.locale = \''.app()->getLocale().'\'
                            where
                                ogp_plan_schedule.deleted_at is null
                                and ogp_plan_schedule.ogp_plan_id = '.$devPlan->id.' ';
            }
        }


//        if($advBoardId) {
//            $itemsCalendarDB = AdvisoryBoardMeeting::with(['translations'])->where('advisory_board_id', (int)$advBoardId->value)->orderBy('next_meeting', 'desc')->get();
            $itemsCalendarDB = \DB::select('
                select events.*
                from (
                    select
                            publication.id as id,
                            publication.id as url_id,
                            \'ogp_news\' as url_type,
                            publication_translations.title as title,
                            publication_translations.short_content as description,
                            case when publication.published_at is not null then publication.published_at::text else null end as start,
                            null as end
                        from publication
                        join publication_translations on publication_translations.publication_id = publication.id and publication_translations.locale = \''.app()->getLocale().'\'
                        where
                            publication.type = '.PublicationTypesEnum::TYPE_OGP_NEWS->value.'
                            and publication.active = true
                            and publication.deleted_at is null
                            and published_at <= \''.Carbon::now()->format('Y-m-d 00:00:00').'\'
                        union all
                            select
                                ogp_plan.id as id,
                                ogp_plan.id as url_id,
                                \'ogp_national_plan\' as url_type,
                                \''.__('ogp.self_evaluation_event').'\' as title,
                                ogp_plan_translations.name as description,
                                ogp_plan.self_evaluation_published_at::text as start,
                                null as end
                            from ogp_plan
                            join ogp_status on ogp_status.id = ogp_plan.ogp_status_id
                            join ogp_plan_translations on ogp_plan_translations.ogp_plan_id = ogp_plan.id and ogp_plan_translations.locale = \''.app()->getLocale().'\'
                            where
                                ogp_plan.active = true
                                and ogp_plan.national_plan = 1
                                and ogp_plan.deleted_at is null
                                and ogp_status.type in ('.OgpStatusEnum::ACTIVE->value.', '.OgpStatusEnum::FINAL->value.')
                                and ogp_plan.self_evaluation_published_at is not null
                                and ogp_plan.self_evaluation_published_at <= now()
                        union all
                            select
                                ogp_plan.id as id,
                                ogp_plan.id as url_id,
                                \'ogp_national_plan\' as url_type,
                                \''.__('ogp.report_evaluation_event').'\' as title,
                                ogp_plan_translations.name as description,
                                ogp_plan.report_evaluation_published_at::text as start,
                                null as end
                            from ogp_plan
                            join ogp_status on ogp_status.id = ogp_plan.ogp_status_id
                            join ogp_plan_translations on ogp_plan_translations.ogp_plan_id = ogp_plan.id and ogp_plan_translations.locale = \''.app()->getLocale().'\'
                            where
                                ogp_plan.active = true
                                and ogp_plan.national_plan = 1
                                and ogp_plan.deleted_at is null
                                and ogp_status.type in ('.OgpStatusEnum::ACTIVE->value.', '.OgpStatusEnum::FINAL->value.')
                                and ogp_plan.report_evaluation_published_at is not null
                                and ogp_plan.report_evaluation_published_at <= now()
                    '.($advBoardId ?
                    'union all
                        select
                        advisory_board_meetings.id as id,
                        advisory_board_meetings.advisory_board_id as url_id,
                        \'adv_board\' as url_type,
                        \''.trans_choice('custom.meetings', 1).'\' as title,
                        advisory_board_meeting_translations.description as description,
                        case when advisory_board_meetings.next_meeting is not null then advisory_board_meetings.next_meeting::text else null end as start,
                        null as end
                    from advisory_board_meetings
                    left join advisory_board_meeting_translations on advisory_board_meeting_translations.advisory_board_meeting_id = advisory_board_meetings.id and advisory_board_meeting_translations.locale = \''.app()->getLocale().'\'
                    where
                        advisory_board_meetings.deleted_at is null
                        and advisory_board_meetings.advisory_board_id = '.(int)$advBoardId->value
                    : ''). $devPlansUnions .'
                ) events
                order by events.start desc
            ');

            if(sizeof($itemsCalendarDB)) {
                foreach ($itemsCalendarDB as $event) {
                    $itemsCalendar[] = array(
                        "id" => $event->id,
                        "title" => $event->title,
                        "url" => $event->url_type == 'adv_board' ? route('advisory-boards.view', $event->url_id)
                            : ($event->url_type == 'ogp_news' ? route('ogp.news.details', $event->url_id)
                                : ($event->url_type == 'ogp_national_plan' ? route('ogp.national_action_plans.show', $event->url_id)
                                    : route('ogp.develop_new_action_plans'))),
                        "description" => $event->description ? clearAfterStripTag(strip_tags(html_entity_decode($event->description))) : '',
                        "description_html" => $event->description ? strip_tags(html_entity_decode($event->description)) : '',
                        "start" => Carbon::parse($event->start)->startOfDay()->format('Y-m-d H:i:s'),
                        "end" => !empty($event->end) ? Carbon::parse($event->end)->endOfDay()->format('Y-m-d H:i:s') : Carbon::parse($event->start)->endOfDay()->format('Y-m-d H:i:s'),
                        "backgroundColor" => (Carbon::parse($event->start)->startOfDay()->format('Y-m-d') > Carbon::now()->startOfDay()->format('Y-m-d') ? '#00a65a' : '#00c0ef'),
                        "borderColor" => (Carbon::parse($event->start)->startOfDay()->format('Y-m-d') > Carbon::now()->startOfDay()->format('Y-m-d') ? '#00a65a' : '#00c0ef'),
                        "oneDay" =>  empty($event->end)
                    );
                }
            }
//        }
        $pageTitle = $this->pageTitle;
        $this->composeBreadcrumbs(null, array(
            ['name' => trans_choice('custom.events', 2), 'url' => '']
        ));
        return $this->view('site.ogp.events', compact('itemsCalendar', 'pageTitle'));
    }

    public function forum(Request $request)
    {
        $advBoardId = Setting::where('name', '=', Setting::OGP_ADV_BOARD_FORUM)->first();
        $customContent = Setting::where('name', '=', Setting::OGP_FORUM_INFO)->first();
        $item = !empty($advBoardId->value) ? AdvisoryBoard::with(['chairmen', 'chairmen.translations',
            'viceChairmen', 'viceChairmen.translations',
            'members', 'members.translations', 'secretaryCouncil',
            'secretaryCouncil.translations', 'meetings',
            'meetings.translations', 'moderators'])->find((int)$advBoardId->value) : null;
        $pageTitle = $this->pageTitle;
        $this->composeBreadcrumbs(null, array(
            ['name' => __('custom.ogp_forum'), 'url' => '']
        ));
        return $this->view('site.ogp.forum', compact('item', 'pageTitle', 'customContent'));
    }

    /**
     * @param $item
     * @param $extraItems
     * @return void
     */
    private function composeBreadcrumbs($item = null, $extraItems = []){
        $customBreadcrumbs = array(
            ['name' => __('custom.open_government_partnership'), 'url' => route('ogp.list')]
        );

//        if($item){
//            $customBreadcrumbs[] = ['name' => $item->name, 'url' => !empty($extraItems) ? route('ogp.view', $item) : null];
//        }
        if(!empty($extraItems)){
            foreach ($extraItems as $eItem){
                $customBreadcrumbs[] = $eItem;
            }
        }
        $this->setBreadcrumbsFull($customBreadcrumbs);
    }

    private function sorters()
    {
        return array(
            'title' => ['class' => 'col-md-3', 'label' => __('custom.title')],
            'publishDate' => ['class' => 'col-md-3', 'label' => __('custom.date_published')],
        );
    }

    private function newsFilters($request, $currentFilter)
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
}
