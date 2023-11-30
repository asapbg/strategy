<?php

namespace App\Policies;

use App\Models\AdvisoryBoardSecretaryCouncil;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AdvisoryBoardSecretaryCouncilPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->canAny('manage.*', 'manage.advisory-boards');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User                          $user
     * @param AdvisoryBoardSecretaryCouncil $secretary
     *
     * @return bool
     */
    public function view(User $user, AdvisoryBoardSecretaryCouncil $secretary): bool
    {
        return $user->canAny('manage.*', 'manage.advisory-boards');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->canAny('manage.*', 'manage.advisory-boards');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User                          $user
     * @param AdvisoryBoardSecretaryCouncil $secretary
     *
     * @return bool
     */
    public function update(User $user, AdvisoryBoardSecretaryCouncil $secretary): bool
    {
        return $user->canAny('manage.*', 'manage.advisory-boards');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User                          $user
     * @param \App\Models\AdvisoryBoardSecretaryCouncil $advisoryBoardSecretaryCouncil
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, AdvisoryBoardSecretaryCouncil $advisoryBoardSecretaryCouncil)
    {
        return $user->canAny('manage.*', 'manage.advisory-boards');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User                          $user
     * @param \App\Models\AdvisoryBoardSecretaryCouncil $advisoryBoardSecretaryCouncil
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, AdvisoryBoardSecretaryCouncil $advisoryBoardSecretaryCouncil)
    {
        return $user->canAny('manage.*', 'manage.advisory-boards');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User                          $user
     * @param \App\Models\AdvisoryBoardSecretaryCouncil $advisoryBoardSecretaryCouncil
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, AdvisoryBoardSecretaryCouncil $advisoryBoardSecretaryCouncil)
    {
        return false;
    }
}
