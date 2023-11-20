<?php

namespace App\Policies;

use App\Models\Consultations\OperationalProgram;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class OperationalProgramPolicy
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
        return $user->canAny(['manage.*', 'manage.advisory']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\OperationalProgram  $operationalProgram
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, OperationalProgram $operationalProgram)
    {
        return $user->canAny(['manage.*', 'manage.advisory']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->canAny(['manage.*', 'manage.advisory']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\OperationalProgram  $operationalProgram
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, OperationalProgram $operationalProgram)
    {
        return $user->canAny(['manage.*', 'manage.advisory']) && !$operationalProgram->public;
    }

    /**
     * Determine whether the user can publish the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\OperationalProgram  $operationalProgram
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function publish(User $user, OperationalProgram $operationalProgram)
    {
        return $user->canAny(['manage.*', 'manage.advisory']) && !$operationalProgram->public;
    }

    /**
     * Determine whether the user can unpublish the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\OperationalProgram  $operationalProgram
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function unPublish(User $user, OperationalProgram $operationalProgram)
    {
        return false;
//        return $user->canAny(['manage.*', 'manage.advisory']) && $operationalProgram->public;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\OperationalProgram  $operationalProgram
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, OperationalProgram $operationalProgram)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\OperationalProgram  $operationalProgram
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, OperationalProgram $operationalProgram)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\OperationalProgram  $operationalProgram
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, OperationalProgram $operationalProgram)
    {
        return false;
    }
}
