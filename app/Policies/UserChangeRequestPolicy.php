<?php

namespace App\Policies;

use App\Models\CustomRole;
use App\Models\User;
use App\Models\UserChangeRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserChangeRequestPolicy
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
     * @param  \App\Models\UserChangeRequest  $userChangeRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, UserChangeRequest $userChangeRequest)
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
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserChangeRequest  $userChangeRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, UserChangeRequest $userChangeRequest)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserChangeRequest  $userChangeRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, UserChangeRequest $userChangeRequest)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserChangeRequest  $userChangeRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, UserChangeRequest $userChangeRequest)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserChangeRequest  $userChangeRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, UserChangeRequest $userChangeRequest)
    {
        return false;
    }

    /**
     * Determine whether the user can withdrew the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserChangeRequest  $userChangeRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function withdrew(User $user, UserChangeRequest $userChangeRequest)
    {
        return $userChangeRequest->user_id == $user->id && $userChangeRequest->status == UserChangeRequest::PENDING;
    }

    /**
     * Determine whether the user can approve the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserChangeRequest  $userChangeRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function approve(User $user, UserChangeRequest $userChangeRequest)
    {
        return $user->hasRole([CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE]) && $userChangeRequest->status == UserChangeRequest::PENDING;
    }
    /**
     * Determine whether the user can reject the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserChangeRequest  $userChangeRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reject(User $user, UserChangeRequest $userChangeRequest)
    {
        return $user->hasRole([CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE]) && $userChangeRequest->status == UserChangeRequest::PENDING;
    }

}
