<?php

namespace App\View\Composers;

use App\Models\Sector;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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

        // Using closure based composers...
//        View::composer('sidebar', function ($view) {

//            $sectors = Sector::select('id', 'name_bg', 'abbr_bg')
//                ->whereActive(true)
//                ->get();
//
//            $view->with('sectors', $sectors);
//        });

        View::composer('partials.footer_front', function ($view) {
            $currentMenuKey = Setting::CONTACT_MAIL_KEY;
            $contactMail = Cache::get($currentMenuKey);
            if( is_null($contactMail) ) {
                $contactMail = Setting::where('name', '=', $currentMenuKey)->first();
                Log::error('Search mail in db');
                if(!$contactMail) {
                    $contactMail = '---';
                } else{
                    $contactMail = $contactMail->value;
                }
                Cache::put($currentMenuKey, $contactMail, 3600);
            }
            $view->with('contactMail', $contactMail);
        });
    }
}
