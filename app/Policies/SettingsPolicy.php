<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingsPolicy
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
        return $user->canAny(['manage.*','manage.settings']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Setting  $settings
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Setting $settings)
    {
        return $user->canAny(['manage.*','manage.settings']);
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
     * @param  \App\Models\Setting  $settings
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Setting $settings)
    {
        return $user->canAny(['manage.*','manage.settings']) && $settings->editable;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Setting  $settings
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Setting $settings)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Setting  $settings
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Setting $settings)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Setting  $settings
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Setting $settings)
    {
        return false;
    }

    public function facebookInit(User $user)
    {
        return $user->canAny(['manage.*','manage.settings']);
    }
}
