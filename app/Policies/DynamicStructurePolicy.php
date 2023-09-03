<?php

namespace App\Policies;

use App\Models\DynamicStructure;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DynamicStructurePolicy
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
        return $user->canAny(['manage.*','manage.dynamic_structures']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DynamicStructure  $dynamicStructure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, DynamicStructure $dynamicStructure)
    {
        return $user->canAny(['manage.*','manage.dynamic_structures']);
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
     * @param  \App\Models\DynamicStructure  $dynamicStructure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, DynamicStructure $dynamicStructure)
    {
        return $user->canAny(['manage.*','manage.dynamic_structures']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DynamicStructure  $dynamicStructure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, DynamicStructure $dynamicStructure)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DynamicStructure  $dynamicStructure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, DynamicStructure $dynamicStructure)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DynamicStructure  $dynamicStructure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, DynamicStructure $dynamicStructure)
    {
        return false;
    }
}
