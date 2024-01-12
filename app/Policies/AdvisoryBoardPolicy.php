<?php

namespace App\Policies;

use App\Models\AdvisoryBoard;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdvisoryBoardPolicy
{

    use HandlesAuthorization;

    /** @var string - Role that determines moderator can operate exact advisory board. */
    const MANAGE_ONE = 'manage.advisory-board';

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->canAny(['manage.*', 'manage.advisory-boards', 'manage.advisory-board']);
    }

    /**
     * General check for super-admin, moderator of whole advisory boards and particular moderator.
     *
     * @param User          $user
     * @param AdvisoryBoard $advisoryBoard
     *
     * @return bool
     */
    private function generalCheckAndModerator(User $user, AdvisoryBoard $advisoryBoard): bool
    {
        $can_any = ['manage.*', 'manage.advisory-boards'];

        if ($advisoryBoard->moderatorCanOperate()) {
            $can_any[] = self::MANAGE_ONE;
        }

        return $user->canAny($can_any);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User          $user
     * @param AdvisoryBoard $advisoryBoard
     *
     * @return bool
     */
    public function view(User $user, AdvisoryBoard $advisoryBoard): bool
    {
        return $this->generalCheckAndModerator($user, $advisoryBoard);
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
        return $user->canAny(['manage.*', 'manage.advisory-boards']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User          $user
     * @param AdvisoryBoard $advisoryBoard
     *
     * @return bool
     */
    public function update(User $user, AdvisoryBoard $advisoryBoard): bool
    {
        return $this->generalCheckAndModerator($user, $advisoryBoard);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User          $user
     * @param AdvisoryBoard $advisoryBoard
     *
     * @return bool
     */
    public function delete(User $user, AdvisoryBoard $advisoryBoard): bool
    {
        return $this->generalCheckAndModerator($user, $advisoryBoard);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User          $user
     * @param AdvisoryBoard $advisoryBoard
     *
     * @return bool
     */
    public function restore(User $user, AdvisoryBoard $advisoryBoard): bool
    {
        return $this->generalCheckAndModerator($user, $advisoryBoard);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User          $user
     * @param AdvisoryBoard $advisoryBoard
     *
     * @return bool
     */
    public function forceDelete(User $user, AdvisoryBoard $advisoryBoard): bool
    {
        return false;
    }
}
