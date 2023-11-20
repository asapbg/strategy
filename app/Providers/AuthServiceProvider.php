<?php

namespace App\Providers;

use App\Models\Consultations\LegislativeProgram;
use App\Models\Consultations\OperationalProgram;
use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Policies\LegislativeProgramPolicy;
use App\Policies\OperationalProgramPolicy;
use App\Policies\PublicConsultationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        PublicConsultation::class => PublicConsultationPolicy::class,
        LegislativeProgram::class => LegislativeProgramPolicy::class,
        OperationalProgram::class => OperationalProgramPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return
                //$user->hasRole(CustomRole::ADMIN_USER_ROLE) ||
                $user->hasRole(CustomRole::SUPER_USER_ROLE) ? true : null;
        });
    }
}
