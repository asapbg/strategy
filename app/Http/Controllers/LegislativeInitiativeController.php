<?php

namespace App\Http\Controllers;

use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\CloseLegislativeInitiativeRequest;
use App\Http\Requests\StoreLegislativeInitiativeRequest;
use App\Http\Requests\UpdateLegislativeInitiativeRequest;
use App\Library\Facebook;
use App\Models\Law;
use App\Models\LegislativeInitiative;
use App\Models\Page;
use App\Models\RegulatoryAct;
use App\Models\Setting;
use App\Models\StrategicDocuments\Institution;
use App\Models\User;
use App\Notifications\LegislativeInitiativeClosed;
use App\Notifications\LegislativeInitiativeSuccessful;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class LegislativeInitiativeController extends AdminController
{

    const LIST_ROUTE = 'legislative_initiatives.index';
    const EDIT_ROUTE = 'legislative_initiatives.edit';
    const STORE_ROUTE = 'legislative_initiatives.store';
    const LIST_VIEW = 'site.legislative_initiatives.index';
    const EDIT_VIEW = 'site.legislative_initiatives.edit';
    const CREATE_VIEW = 'site.legislative_initiatives.create';
    const SHOW_VIEW = 'site.legislative_initiatives.view';

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->title_singular = __('custom.legislative_initiatives');
        $this->pageTitle = __('custom.legislative_initiatives');
    }

    /**
     * Show the public consultations.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $rssUrl = config('feed.feeds.legislative_initiative.url');
        $institutions = Institution::select('id')->whereHas('laws')->orderBy('id')->with('translation')->get();
        $laws = Law::select('id')->Active()->orderByTranslation('name')->with('translation')->get();
        $countResults = $request->get('count_results', config('app.default_paginate'));
        $keywords = $request->offsetGet('keywords');
        $institution = $request->offsetGet('institution');
        $law = $request->offsetGet('law');

        $order_by = $request->filled('order_by') ? $request->input('order_by') : 'date';
        $order_by_direction = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : null);

        $items = LegislativeInitiative::select(['legislative_initiative.*'])
            ->with(['user', 'law', 'law.translation', 'likes', 'dislikes'])
            ->join('law', 'law.id', '=', 'legislative_initiative.law_id')
            ->join('law_institution', function ($query) use ($institution) {
                $query->on('law_institution.law_id', '=', 'law.id')->when(!empty($institution), function ($query) use ($institution) {
                    $query->whereIn('law_institution.institution_id', $institution);
                });
            })
            ->join('institution', 'law_institution.institution_id', '=', 'institution.id')
            ->join('institution_translations', function ($q) {
                $q->on('institution_translations.institution_id', '=', 'institution.id')->where('institution_translations.locale', '=', app()->getLocale());
            })
            ->when(!empty($law), function ($query) use ($law) {
                $query->whereIn('law.id', $law);
            })
            ->when(!empty($keywords), function ($query) use ($keywords) {
                $query->orWhere('legislative_initiative.description', 'ilike', '%' . $keywords . '%')
                    ->orWhereHas('user', function ($query) use ($keywords) {
                        $query->where('first_name', 'ilike', '%' . $keywords . '%');
                        $query->orWhere('middle_name', 'ilike', '%' . $keywords . '%');
                        $query->orWhere('last_name', 'ilike', '%' . $keywords . '%');
                    });
            })
            ->when(!empty($order_by), function ($query) use ($order_by, $order_by_direction) {
                $direction = !in_array($order_by_direction, ['asc', 'desc']) ? 'asc' : $order_by_direction;

                if ($order_by == 'institutions') {
                    $query->orderBy('institution_translations.name', $direction);
                } else {
                    $query->orderBy('legislative_initiative.created_at', $direction);
                }
            })
            ->groupBy('legislative_initiative.id')
            ->paginate($countResults);

        $pageTitle = $this->pageTitle;
        $this->composeBreadcrumbs(null, array(['name' => __('site.all_legislative_initiative'), 'url' => '']));
        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_LI . '_' . app()->getLocale())->first();

        $defaultDirection = $request->get('direction');

        $requestFilter = $request->all();
        $hasSubscribeEmail = $this->hasSubscription(null, LegislativeInitiative::class, $requestFilter);
        $hasSubscribeRss = false;
        $this->setSeo(__('site.seo_title'), trans_choice('custom.legislative_initiatives', 2), '', array('title' => __('site.seo_title'), 'description' => trans_choice('custom.legislative_initiatives', 2), 'img' => LegislativeInitiative::DEFAULT_IMG));

        return $this->view(self::LIST_VIEW, compact('items', 'institutions', 'pageTitle', 'pageTopContent',
            'laws', 'defaultDirection', 'requestFilter', 'hasSubscribeEmail', 'hasSubscribeRss', 'rssUrl'));
    }


    public function create(Request $request)
    {
        if ($request->user()->cannot('create', LegislativeInitiative::class)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $regulatoryActs = RegulatoryAct::orderBy('id')->get();
        $translatableFields = LegislativeInitiative::translationFieldsProperties();
        $item = new LegislativeInitiative();
        $pageTitle = $this->pageTitle;

        $lawWithActivePc = array();
        //get law with active public consultation
        $lawWithPcDB = Law::with(['translation', 'pc' => function ($q) {
            $q->ActivePeriodPublic();
        }])->get();

        if ($lawWithPcDB->count()) {
            foreach ($lawWithPcDB as $r) {
                if ($r->pc->count()) {
                    foreach ($r->pc as $pc) {
                        if (!isset($lawWithActivePc[$r->id])) {
                            $lawWithActivePc[$r->id] = array();
                        }
                        $lawWithActivePc[$r->id][] = [
                            'id' => $pc->id,
                            'name' => $pc->title,
                            'url' => route('public_consultation.view', $pc->id),
                        ];
                    }
                }
            }
        }

        $institutions = Institution::optionsListWithAttr();
        $this->composeBreadcrumbs(null, array(['name' => __('site.new_legislative_initiative'), 'url' => '']));
        $this->setSeo(__('site.seo_title'), __('site.new_legislative_initiative'), '', array('title' => __('site.seo_title'), 'description' => __('site.new_legislative_initiative'), 'img' => LegislativeInitiative::DEFAULT_IMG));

        $settingsCap = Setting::where('name', '=', Setting::OGP_LEGISLATIVE_INIT_REQUIRED_LIKES)
            ->where('section', '=', Setting::OGP_LEGISLATIVE_INIT_SECTION)->first();
        $cap = $settingsCap ? $settingsCap->value : 50;

        $settingsSupportDays = Setting::where('name', '=', Setting::OGP_LEGISLATIVE_INIT_SUPPORT_IN_DAYS)
            ->where('section', '=', Setting::OGP_LEGISLATIVE_INIT_SECTION)->first();
        $supportDays = $settingsSupportDays ? $settingsSupportDays->value : 50;

        return $this->view(self::CREATE_VIEW, compact('regulatoryActs', 'translatableFields', 'item', 'pageTitle', 'institutions', 'lawWithActivePc', 'cap', 'supportDays'));
    }

    /**
     * @param Request $request
     * @param LegislativeInitiative $item
     *
     */
    public function edit(Request $request, LegislativeInitiative $item)
    {
        if ($request->user()->cannot('update', $item)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = LegislativeInitiative::translationFieldsProperties();
        $regulatoryActs = RegulatoryAct::all();

        $pageTitle = $this->pageTitle;
        $this->composeBreadcrumbs($item);
        $institutions = Institution::optionsListWithAttr();
        $this->setSeo($item->facebookTitle, $item->ogDescription, '', array('title' => $item->facebookTitle, 'description' => $item->ogDescription, 'img' => LegislativeInitiative::DEFAULT_IMG));
        return $this->view(self::EDIT_VIEW, compact('item', 'pageTitle', 'storeRouteName', 'listRouteName', 'translatableFields', 'regulatoryActs', 'institutions'));
    }

    public function store(StoreLegislativeInitiativeRequest $request)
    {
        $validated = $request->validated();
        if ($request->user()->cannot('create', LegislativeInitiative::class)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        DB::beginTransaction();
        try {
            $selectedInstitutions = array_filter($validated['institutions'] ?? [], function ($v) {
                return (int)$v > 0;
            });
            unset($validated['institutions']);
            $validated['author_id'] = auth()->user()->id;

            $settingsCap = Setting::where('name', '=', Setting::OGP_LEGISLATIVE_INIT_REQUIRED_LIKES)
                ->where('section', '=', Setting::OGP_LEGISLATIVE_INIT_SECTION)->first();
            $validated['cap'] = $settingsCap ? $settingsCap->value : 50;

            $settingsSupportDays = Setting::where('name', '=', Setting::OGP_LEGISLATIVE_INIT_SUPPORT_IN_DAYS)
                ->where('section', '=', Setting::OGP_LEGISLATIVE_INIT_SECTION)->first();
            $validated['active_support'] = Carbon::now()->addDays($settingsSupportDays ? $settingsSupportDays->value : 50)->endOfDay()->format('Y-m-d H:i:s');

            $legislativeInitiative = new LegislativeInitiative();
            $legislativeInitiative->fill($validated);
            $legislativeInitiative->save();

            //Set all if selected all
            if (!sizeof($selectedInstitutions)) {
                $selectedInstitutions = Law::find($validated['law_id'])->institutions->pluck('id')->toArray();
            }
            $legislativeInitiative->institutions()->sync($selectedInstitutions);

            $legislativeInitiative->votes()->create([
                'user_id' => $request->user()->id,
                'is_like' => 1
            ]);

            if (Setting::allowPostingToFacebook()) {
                $facebookApi = new Facebook();
                $facebookApi->postToFacebook($legislativeInitiative);
            }

            $legislativeInitiative->refresh();
            if ($legislativeInitiative->cap <= $legislativeInitiative->countSupport()) {
                $legislativeInitiative->status = LegislativeInitiativeStatusesEnum::STATUS_SEND->value;
                $legislativeInitiative->ready_to_send = 1;
                $legislativeInitiative->end_support_at = Carbon::now()->format('Y-m-d H:i:s');
                $legislativeInitiative->save();

                //Send notification to author and all voted for successful initiative
                $likesUserIds = $legislativeInitiative->likes->pluck('user_id')->toArray();
                if (sizeof($likesUserIds)) {
                    $users = User::whereIn('id', $likesUserIds)->get();
                    if ($users->count()) {
                        foreach ($users as $n) {
                            $n->notify(new LegislativeInitiativeSuccessful($legislativeInitiative));
                        }
                    }
                }
                if ($legislativeInitiative->user && !in_array($legislativeInitiative->user->id, $likesUserIds)) {
                    $legislativeInitiative->user->notify(new LegislativeInitiativeSuccessful($legislativeInitiative));
                }
            }
            DB::commit();

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.legislative_initiatives_list', 1) . " " . __('messages.created_successfully_f'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function show(LegislativeInitiative $item)
    {
        $rssUrl = route('rss.legislative_initiative.item', $item->id);
        $pageTitle = $this->pageTitle;
        $this->composeBreadcrumbs($item, array(['name' => __('site.all_legislative_initiative'), 'url' => '']));

        $pageTopContent = Setting::where('name', '=', Setting::PAGE_CONTENT_LI . '_' . app()->getLocale())->first();
        $needSupport = ($item->cap - $item->countSupport());

        $hasSubscribeEmail = $this->hasSubscription($item);
        $hasSubscribeRss = false;

        $this->setSeo($item->facebookTitle, $item->ogDescription, '', array('title' => $item->facebookTitle, 'description' => $item->ogDescription, 'img' => LegislativeInitiative::DEFAULT_IMG));

        return $this->view(self::SHOW_VIEW, compact('item', 'pageTopContent', 'pageTitle', 'needSupport',
            'hasSubscribeEmail', 'hasSubscribeRss', 'rssUrl'));
    }

    public function update(UpdateLegislativeInitiativeRequest $request, LegislativeInitiative $item)
    {
        $validated = $request->validated();

        if ($request->user()->cannot('update', $item)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        DB::beginTransaction();
        try {
            $selectedInstitutions = array_filter($validated['institutions'] ?? [], function ($v) {
                return (int)$v > 0;
            });
            unset($validated['institutions']);

            $item->fill($validated);
            $item->save();

            //Set all if selected all
            if (!sizeof($selectedInstitutions)) {
                $selectedInstitutions = Law::find($validated['law_id'])->institutions->pluck('id')->toArray();
            }
            $item->institutions()->sync($selectedInstitutions);
            DB::commit();

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.legislative_initiatives_list', 1) . " " . __('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function close(CloseLegislativeInitiativeRequest $request, LegislativeInitiative $item)
    {
        if ($request->user()->cannot('close', $item)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $item->setStatus(LegislativeInitiativeStatusesEnum::STATUS_CLOSED);
            $item->end_support_at = Carbon::now()->format('Y-m-d H:i:s');
            $item->save();

            //Send notification to all voted for closed initiative
            $likesUserIds = $item->likes->pluck('user_id')->toArray();
            if (sizeof($likesUserIds)) {
                $users = User::whereIn('id', $likesUserIds)->get();
                if ($users->count()) {
                    foreach ($users as $n) {
                        $n->notify(new LegislativeInitiativeClosed($item, 'closed'));
                    }
                }
            }

            return back()->with('success', trans_choice('custom.legislative_initiatives', 1) . " " . __('messages.close_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect(route(self::LIST_ROUTE, $item))->with('danger', __('messages.system_error'));
        }
    }

    public function destroy(Request $request, LegislativeInitiative $item)
    {
        if ($request->user()->cannot('delete', $item)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        try {
            $item->delete();
            //Send notification to all voted for closed initiative
            $likesUserIds = $item->likes->pluck('user_id')->toArray();
            if (sizeof($likesUserIds)) {
                $users = User::whereIn('id', $likesUserIds)->get();
                if ($users->count()) {
                    foreach ($users as $n) {
                        $n->notify(new LegislativeInitiativeClosed($item, 'deleted', false));
                    }
                }
            }
            $redirectTo = route('legislative_initiatives.index');
            return redirect($redirectTo)
                ->with('success', trans_choice('custom.legislative_initiatives', 1) . " " . __('messages.deleted_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect(route(self::LIST_ROUTE, $item))->with('danger', __('messages.system_error'));
        }
    }

    public function info()
    {
        $page = Page::with(['files' => function ($q) {
            $q->where('locale', '=', app()->getLocale());
        }])
            ->where('system_name', '=', Page::LEGISLATIVE_INITIATIVE_INFO)
            ->first();
        if (!$page) {
            abort(404);
        }
        $pageTitle = $this->pageTitle;
//        $this->setSeo($page->meta_title, $page->meta_description, $page->meta_keyword);
        $this->setSeo($page->meta_title ?? $page->name, $page->meta_description ?? $page->short_content, $page->meta_keyword, array('title' => $page->meta_title ?? $page->name, 'img' => Page::DEFAULT_IMG));

        $this->composeBreadcrumbs(null, array(['name' => $page->name, 'url' => '']));
        return $this->view('site.ogp.page', compact('page', 'pageTitle'));
    }

    /**
     * @param $item
     * @param $extraItems
     * @return void
     */
    private function composeBreadcrumbs($item = null, $extraItems = [])
    {
        $customBreadcrumbs = array(
            ['name' => __('custom.legislative_initiatives'), 'url' => route('legislative_initiatives.index')]
        );

        if (!empty($extraItems)) {
            foreach ($extraItems as $eItem) {
                $customBreadcrumbs[] = $eItem;
            }
        }

        if ($item) {
            $customBreadcrumbs[] = [
                'name' => (__('custom.change_f') . ' ' . __('custom.in') . ' ' . $item->law?->name),
                'url' => (!empty($extraItems) ? route('legislative_initiatives.view', $item) : null)
            ];
        }

        $this->setBreadcrumbsFull($customBreadcrumbs);
    }
}
