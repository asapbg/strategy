<?php

namespace App\Policies;

use App\Models\AdvisoryBoardMember;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdvisoryBoardMemberPolicy
{

    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User                $user
     * @param AdvisoryBoardMember $advisoryBoardMember
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, AdvisoryBoardMember $advisoryBoardMember)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->canAny('manage.*', 'manage.advisory-boards');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User                $user
     * @param AdvisoryBoardMember $member
     *
     * @return bool
     */
    public function update(User $user, AdvisoryBoardMember $member): bool
    {
        return $user->canAny('manage.*', 'manage.advisory-boards');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User                $user
     * @param AdvisoryBoardMember $member
     *
     * @return bool
     */
    public function delete(User $user, AdvisoryBoardMember $member): bool
    {
        return $user->canAny('manage.*', 'manage.advisory-boards');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User                $user
     * @param AdvisoryBoardMember $advisoryBoardMember
     *
     * @return bool
     */
    public function restore(User $user, AdvisoryBoardMember $advisoryBoardMember): bool
    {
        return $user->canAny('manage.*', 'manage.advisory-boards');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User                $user
     * @param AdvisoryBoardMember $advisoryBoardMember
     *
     * @return bool
     */
    public function forceDelete(User $user, AdvisoryBoardMember $advisoryBoardMember): bool
    {
        return false;
    }
}
