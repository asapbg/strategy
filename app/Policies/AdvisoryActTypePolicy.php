<?php

namespace App\Policies;

use App\Models\AdvisoryActType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdvisoryActTypePolicy
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
        return $user->canAny(['manage.*', 'manage.nomenclatures', 'manage.advisory-boards.nomenclatures']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AdvisoryActType  $advisoryActType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, AdvisoryActType $advisoryActType)
    {
        return $user->canAny(['manage.*', 'manage.nomenclatures', 'manage.advisory-boards.nomenclatures']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->canAny(['manage.*', 'manage.nomenclatures', 'manage.advisory-boards.nomenclatures']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AdvisoryActType  $advisoryActType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, AdvisoryActType $advisoryActType)
    {
        return $user->canAny(['manage.*', 'manage.nomenclatures', 'manage.advisory-boards.nomenclatures']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AdvisoryActType  $advisoryActType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, AdvisoryActType $advisoryActType)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AdvisoryActType  $advisoryActType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, AdvisoryActType $advisoryActType)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AdvisoryActType  $advisoryActType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, AdvisoryActType $advisoryActType)
    {
        return false;
    }
}
