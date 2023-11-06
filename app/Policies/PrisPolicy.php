<?php

namespace App\Policies;

use App\Models\Pris;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrisPolicy
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
        return $user->canAny(['manage.*','manage.pris']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pris  $pris
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Pris $pris)
    {
        return $user->canAny(['manage.*','manage.pris']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->canAny(['manage.*','manage.pris']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pris  $pris
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Pris $pris)
    {
        return $user->canAny(['manage.*','manage.pris']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pris  $pris
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Pris $pris)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pris  $pris
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Pris $pris)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pris  $pris
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Pris $pris)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pris  $pris
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function publish(User $user, Pris $pris)
    {
        return $user->canAny(['manage.*','manage.pris']) && empty($pris->published_at);
    }
}
