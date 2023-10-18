<?php

namespace App\Policies;

use App\Models\CustomActivity;
use App\Models\CustomRole;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomActivityPolicy
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
        return $user->hasRole([CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE]);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomActivity  $customActivity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CustomActivity $customActivity)
    {
        return $user->hasRole([CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE]);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomActivity  $customActivity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CustomActivity $customActivity)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomActivity  $customActivity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CustomActivity $customActivity)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomActivity  $customActivity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CustomActivity $customActivity)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomActivity  $customActivity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CustomActivity $customActivity)
    {
        return false;
    }
}
