<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFour();
        Model::unguard(true);

        Activity::saving(function (Activity $activity){
            $agent = request()->headers && !empty(request()->headers->get('user-agent')) ? request()->headers->get('user-agent') : null;
            $activity->properties = $activity->properties->put('ip', request()->ip())->put('agent', $agent);
        });
    }
}
