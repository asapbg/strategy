<?php

namespace App\Http\Controllers;

use App\Models\AdvisoryActType;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryChairmanType;
use App\Models\AuthorityAdvisoryBoard;
use App\Models\FieldOfAction;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvisoryBoardController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->title_singular = __('custom.legislative_initiatives');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $pageTitle = trans_choice('custom.advisory_boards', 2);
        $slider = ['title' => $pageTitle, 'img' => '/img/ms-w-2023.jpg'];

        $field_of_actions = FieldOfAction::advisoryBoard()->select('field_of_actions.*')
            ->whereLocale(app()->getLocale())
            ->joinTranslation(FieldOfAction::class)
            ->with(['translation'])
            ->orderBy('id')
            ->get();
        $authority_advisory_boards = AuthorityAdvisoryBoard::select('authority_advisory_board.*')
            ->whereLocale(app()->getLocale())
            ->joinTranslation(AuthorityAdvisoryBoard::class)
            ->with(['translation'])
            ->orderBy('id')
            ->get();
        $advisory_act_types = AdvisoryActType::select('advisory_act_type.*')
            ->whereLocale(app()->getLocale())
            ->joinTranslation(AdvisoryActType::class)
            ->with(['translation'])
            ->orderBy('id')
            ->get();
        $advisory_chairman_types = AdvisoryChairmanType::select('advisory_chairman_type.*')
            ->whereLocale(app()->getLocale())
            ->joinTranslation(AdvisoryChairmanType::class)
            ->with(['translation'])
            ->orderBy('id')
            ->get();
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_ADVISORY_BOARDS . '_' . app()->getLocale())->first();
        $status = request()->offsetGet('status');

        $is_search = $request->has('search');
        $filter_field_of_action = $request->get('filter_field_of_action');
        $filter_authority = $request->get('filter_authority');
        $filter_act_of_creation = $request->get('filter_act_of_creation');
        $filter_chairman_type = $request->get('filter_chairman_type');
        $keywords = $request->get('keywords');

        $sort = ($request->offsetGet('sort'))
            ? $request->offsetGet('sort')
            : "DESC";
        $order_by = ($request->offsetGet('order_by'))
            ? $request->offsetGet('order_by')
            : "id";
        $sort_table = (in_array($order_by, AdvisoryBoard::TRANSLATABLE_FIELDS))
            ? "advisory_board_translations"
            : "advisory_boards";
        $paginate = $request->filled('paginate') ? $request->get('paginate') : 5;

        $advisory_boards = AdvisoryBoard::select('advisory_boards.*')
            ->whereLocale(app()->getLocale())
            ->joinTranslation(AdvisoryBoard::class)
            ->with(['policyArea', 'translations'])
            ->where(function ($query) use ($keywords) {
                $query->when(!empty($keywords) && is_numeric($keywords), function ($query) use ($keywords) {
                    $query->where('id', $keywords);
                })
                    ->when(!empty($keywords) && !is_numeric($keywords), function ($query) use ($keywords) {
                        $query->whereHas('translations', function ($query) use ($keywords) {
                            $query->where('name', 'ilike', '%' . $keywords . '%');
                        });
                    });
            })
            ->when($filter_field_of_action, function ($query) use ($filter_field_of_action) {
                $query->where('policy_area_id', $filter_field_of_action);
            })
            ->when($filter_authority, function ($query) use ($filter_authority) {
                $query->where('authority_id', $filter_authority);
            })
            ->when($filter_act_of_creation, function ($query) use ($filter_act_of_creation) {
                $query->where('advisory_act_type_id', $filter_act_of_creation);
            })
            ->when($filter_chairman_type, function ($query) use ($filter_chairman_type) {
                $query->where('advisory_chairman_type_id', $filter_chairman_type);
            })
            ->when($status != '', function ($query) use ($status) {
                $query->where('active', (bool)$status);
            })
            ->where('public', true)
            ->orderBy("$sort_table.$order_by", $sort)
//            ->orderBy('id', 'desc')
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
            'slider'
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
        $item = $item->where('id', $item->id)->with(['customSections' => function ($query) {
            $query->with(['files' => fn($query) => $query->with('versions'), 'translations']);
        }, 'npos' => function ($query) {
            $query->with('translations');
        }, 'members' => function($query) {
            $query->with('translations');
        }, 'meetings' => function($query) {
            $query->with(['translations', 'siteFiles' => function($query) {
                $query->with('versions');
            }]);
        }, 'secretariat' => function($query) {
            $query->with(['translations', 'siteFiles']);
        }, 'workingProgram' => function($query) {
            $query->with(['translations', 'siteFiles']);
        }])->first();

        return view('site.advisory-boards.view', compact('item'));
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
}
