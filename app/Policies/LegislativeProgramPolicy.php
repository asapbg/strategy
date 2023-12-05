<?php

namespace App\Policies;

use App\Models\Consultations\LegislativeProgram;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class LegislativeProgramPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->canAny(['manage.*', 'manage.legislative_operational_programs']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\LegislativeProgram  $legislativeProgram
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, LegislativeProgram $legislativeProgram)
    {
        return $user->canAny(['manage.*', 'manage.legislative_operational_programs']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->canAny(['manage.*', 'manage.legislative_operational_programs']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\LegislativeProgram  $legislativeProgram
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, LegislativeProgram $legislativeProgram)
    {
        $now = Carbon::now()->format('Y-m-d');
        return $user->canAny(['manage.*', 'manage.legislative_operational_programs']) && !$legislativeProgram->public;
    }

    /**
     * Determine whether the user can publish the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\LegislativeProgram  $legislativeProgram
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function publish(User $user, LegislativeProgram $legislativeProgram)
    {
        return $user->canAny(['manage.*', 'manage.legislative_operational_programs']) && !$legislativeProgram->public;
    }

    /**
     * Determine whether the user can unpublish the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\LegislativeProgram  $legislativeProgram
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function unPublish(User $user, LegislativeProgram $legislativeProgram)
    {
        //return false;
        return $user->canAny(['manage.*', 'manage.legislative_programs']) && $legislativeProgram->public;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\LegislativeProgram  $legislativeProgram
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, LegislativeProgram $legislativeProgram)
    {
        return $user->canAny(['manage.*', 'manage.legislative_operational_programs.delete']) && !$legislativeProgram->public;;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\LegislativeProgram  $legislativeProgram
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, LegislativeProgram $legislativeProgram)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\LegislativeProgram  $legislativeProgram
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, LegislativeProgram $legislativeProgram)
    {
        return false;
    }
}
