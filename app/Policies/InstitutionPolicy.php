<?php

namespace App\Policies;

use App\Models\StrategicDocuments\Institution;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InstitutionPolicy
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
        return $user->canAny(['manage.*','manage.nomenclatures']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StrategicDocuments\Institution  $institution
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Institution $institution)
    {
        return $user->canAny(['manage.*','manage.nomenclatures']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->canAny(['manage.*','manage.nomenclatures']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StrategicDocuments\Institution  $institution
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Institution $institution)
    {
        return $user->canAny(['manage.*','manage.nomenclatures']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StrategicDocuments\Institution  $institution
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Institution $institution)
    {
        return $user->canAny(['manage.*','manage.nomenclatures']);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StrategicDocuments\Institution  $institution
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Institution $institution)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StrategicDocuments\Institution  $institution
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Institution $institution)
    {
        return false;
    }
}
