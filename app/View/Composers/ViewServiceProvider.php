<?php

namespace App\View\Composers;

use App\Enums\OgpStatusEnum;
use App\Enums\OldNationalPlanEnum;
use App\Enums\PageModulesEnum;
use App\Models\OgpPlan;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.site', function ($view) {
            $facebookAppIdKey = Setting::FACEBOOK_APP_ID;
            $facebookAppId = Cache::get($facebookAppIdKey);
            if (is_null($facebookAppId)) {
                $settingsFb = Setting::where('name', '=', Setting::FACEBOOK_APP_ID)
                    ->where('section', '=', Setting::FACEBOOK_SECTION)->get()->first();
                $facebookAppId = $settingsFb ? $settingsFb->value : '';
                Cache::put($facebookAppIdKey, $facebookAppId, 3600);
            }

            $vo_font_percent = (int)Session::get('vo_font_percent', 100);
            $vo_high_contrast = (int)Session::get('vo_high_contrast', 0);
            $vo_can_reset = $vo_font_percent != 100 || $vo_high_contrast == 1;

            $view->with(
                [
                    'facebookAppId' => $facebookAppId,
                    'vo_font_percent' => $vo_font_percent,
                    'vo_high_contrast' => $vo_high_contrast,
                    'vo_can_reset' => $vo_can_reset,
                ]
            );
        });

        View::composer('site.legislative_initiatives.side_menu', function ($view) {
            $ogpLibraryKey = Page::CACHE_MODULE_PAGES_OGP;
            $library = Cache::get($ogpLibraryKey);
            if (is_null($library)) {
                $library = Page::with(['translations'])
                    ->where('module_enum', '=', PageModulesEnum::MODULE_OGP->value)
                    ->orderBy('order_idx', 'asc')
                    ->get();
                Cache::put($ogpLibraryKey, $library, 3600);
            }

            $view->with('library', $library);

            $nationalPlans = [
                ['id' => OldNationalPlanEnum::FIRST->value, 'url' => route('ogp.national_action_plans.show.old', OldNationalPlanEnum::FIRST->value), 'label' => OldNationalPlanEnum::nameByValue(OldNationalPlanEnum::FIRST->value), 'old' => true],
                ['id' => OldNationalPlanEnum::SECOND->value, 'url' => route('ogp.national_action_plans.show.old', OldNationalPlanEnum::SECOND->value), 'label' => OldNationalPlanEnum::nameByValue(OldNationalPlanEnum::SECOND->value), 'old' => true],
                ['id' => OldNationalPlanEnum::THIRD->value, 'url' => route('ogp.national_action_plans.show.old', OldNationalPlanEnum::THIRD->value), 'label' => OldNationalPlanEnum::nameByValue(OldNationalPlanEnum::THIRD->value), 'old' => true],
            ];
            $nationalPlan = OgpPlan::Active()
                ->National()
                ->Visible()
                ->orderBy('from_date', 'asc')
                ->get();

            if ($nationalPlan->count()) {
                foreach ($nationalPlan as $plan) {
                    $nationalPlans[] = ['url' => route('ogp.national_action_plans.show', $plan->id), 'id' => $plan->id, 'label' => $plan->name, 'old' => false];
                }
            }
            //TODO add other previews plans

            $view->with('nationalPlans', $nationalPlans);

            $developPlan = OgpPlan::select('ogp_plan.*')
                ->Active()
                ->join('ogp_status', 'ogp_plan.ogp_status_id', '=', 'ogp_status.id')
                ->leftJoin('ogp_plan_translations', function ($j) {
                    $j->on('ogp_plan_translations.ogp_plan_id', '=', 'ogp_plan.id')
                        ->where('ogp_plan_translations.locale', '=', app()->getLocale());
                })
                ->where('ogp_status.type', OgpStatusEnum::IN_DEVELOPMENT->value)
                ->orderBy('ogp_plan.created_at', 'asc')
                ->count();
            $view->with('developPlan', $developPlan);
        });

        View::composer('impact_assessment.sidebar', function ($view) {
            $ogpLibraryKey = Page::CACHE_MODULE_PAGES_IMPACT_ASSESSMENT;
            $library = Cache::get($ogpLibraryKey);
            if (is_null($library)) {
                $library = Page::with(['translations'])
                    ->where('module_enum', '=', PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value)
                    ->orderBy('order_idx', 'asc')
                    ->get();
                Cache::put($ogpLibraryKey, $library, 3600);
            }

            $view->with('library', $library);
        });

        View::composer('partials.footer_front', function ($view) {
            $currentMenuKey = Setting::CONTACT_MAIL_KEY;
            $contactMail = Cache::get($currentMenuKey);
            if (is_null($contactMail)) {
                $contactMail = Setting::where('name', '=', $currentMenuKey)->first();
                //Log::error('Search mail in db');
                if (!$contactMail) {
                    $contactMail = '---';
                } else {
                    $contactMail = $contactMail->value;
                }
                Cache::put($currentMenuKey, $contactMail, 3600);
            }
            $view->with('contactMail', $contactMail);

            $footerPagesCacheKey = Page::CACHE_FOOTER_PAGES_KEY . '_' . app()->getLocale();
            $footerPages = Cache::get($footerPagesCacheKey);
            if (is_null($footerPages)) {
                $footerPages = [];
                $footerPagesQ = Page::with(['translations'])->InFooter()->get();
                if ($footerPagesQ->count()) {
                    foreach ($footerPagesQ as $page) {
                        //case in module
                        if ($page->module_enum && !$page->is_system) {
                            switch ($page->module_enum) {
                                case PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value:
                                    $footerPages[] = ['name' => $page->name, 'url' => route('impact_assessment.library.view', ['slug' => $page->slug])];
                                    break;
                                case PageModulesEnum::MODULE_OGP->value:
                                    $footerPages[] = ['name' => $page->name, 'url' => route('ogp.library.view', ['slug' => $page->slug])];
                                    break;
                            }
                        } elseif ($page->is_system) {
                            //case by system name
                            switch ($page->system_name) {
                                case Page::ADV_BOARD_DOCUMENTS:
                                    $footerPages[] = ['name' => $page->name, 'url' => route('advisory-boards.documents')];
                                    break;
                                case Page::ADV_BOARD_INFO:
                                    $footerPages[] = ['name' => $page->name, 'url' => route('advisory-boards.info')];
                                    break;
                                case Page::IA_INFO:
                                    $footerPages[] = ['name' => $page->name, 'url' => route('impact_assessment.index')];
                                    break;
                            }
                        } else {
                            $footerPages[] = ['name' => $page->name, 'url' => route('page.view', ['slug' => $page->slug])];
                        }
                    }
                }
                Cache::put($footerPagesCacheKey, $footerPages, 3600);
            }
            $view->with('footerPages', $footerPages);

            //Terms pages
            $footerTermsPagesCacheKey = Page::CACHE_FOOTER_TERMS_PAGES . '_' . app()->getLocale();
            $footerTermsPages = Cache::get($footerTermsPagesCacheKey);
            if (is_null($footerTermsPages)) {
                $footerTermsPages = [];
                $termsPageNames = [Page::ACCESS_POLICY, Page::PRIVACY_POLICY, Page::TERMS, Page::COOKIES];
                $pages = Page::with(['translations'])->isActive()->whereIn('system_name', $termsPageNames)->get();
                if ($pages->count()) {
                    foreach ($termsPageNames as $systemName) {
                        foreach ($pages as $p) {
                            if ($systemName == $p->system_name) {
                                $footerTermsPages[] = ['name' => $p->name, 'url' => route('page.view', ['slug' => $p->slug])];
                            }
                        }
                    }
                }
                Cache::put($footerTermsPagesCacheKey, $footerTermsPages, 3600);
            }
            $view->with('footerTermsPages', $footerTermsPages);

        });
    }
}
