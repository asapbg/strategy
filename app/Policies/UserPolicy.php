<?php

namespace App\Policies;

use App\Models\CustomRole;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasRole([CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE]);
    }

    /**
     * Determine whether the user can create Api models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function createApiUser(User $user)
    {
        return $user->hasRole([CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE]);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        return $user->hasRole([CustomRole::SUPER_USER_ROLE]) || ($user->hasRole([CustomRole::ADMIN_USER_ROLE]) && !$model->hasRole([CustomRole::SUPER_USER_ROLE]));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        return ($user->hasRole([CustomRole::SUPER_USER_ROLE]) || ($user->hasRole([CustomRole::ADMIN_USER_ROLE]) && !$model->hasRole([CustomRole::SUPER_USER_ROLE]))) && is_null($model->deleted_at);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {
        return ($user->hasRole([CustomRole::SUPER_USER_ROLE]) || ($user->hasRole([CustomRole::ADMIN_USER_ROLE]) && !$model->hasRole([CustomRole::SUPER_USER_ROLE]))) && !is_null($model->deleted_at);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        return false;
    }

    /**
     * Determine whether the user can export models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function export(User $user)
    {
        return $user->hasRole([CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE]);
    }

//    Strategic documents Users

    /**
     * Determine whether the user can view strategic documents users.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewSdAny(User $user)
    {
        return $user->hasRole([CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE, CustomRole::MODERATOR_STRATEGIC_DOCUMENTS]);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function createSd(User $user)
    {
        return $user->hasRole([CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE, CustomRole::MODERATOR_STRATEGIC_DOCUMENTS]);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateSd(User $user, User $model)
    {
        return $user->hasRole([CustomRole::SUPER_USER_ROLE])
            || ($user->hasRole([CustomRole::ADMIN_USER_ROLE]) && !$model->hasRole([CustomRole::SUPER_USER_ROLE]))
            || ($user->hasRole([CustomRole::MODERATOR_STRATEGIC_DOCUMENTS]) && !$model->hasRole([CustomRole::SUPER_USER_ROLE]) && $model->hasRole([CustomRole::MODERATOR_STRATEGIC_DOCUMENT]));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteSd(User $user, User $model)
    {
        return false;
    }

}
