<?php

namespace App\Policies;

use App\Enums\PublicationTypesEnum;
use App\Models\CustomRole;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PublicationPolicy
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
        return $user->canAny(['manage.*','manage.library']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Publication $publication)
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
        return $user->canAny(['manage.*','manage.library']);
    }


    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Publication $publication)
    {
        return $user->canAny(['manage.*','manage.library']) && $publication->type != PublicationTypesEnum::TYPE_ADVISORY_BOARD->value;
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Publication $publication)
    {
        return $user->canAny(['manage.*','manage.library']) && $publication->type != PublicationTypesEnum::TYPE_ADVISORY_BOARD->value;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Publication $publication)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Publication $publication)
    {
        return false;
    }


//    ===========================
//    Консултативни съвети
//     ==========================

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */

    public function viewAnyAdvBoard(User $user)
    {
        return $user->hasAnyRole([CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE, CustomRole::MODERATOR_ADVISORY_BOARD, CustomRole::MODERATOR_ADVISORY_BOARDS]);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function createAdvBoard(User $user)
    {
        $roles = [CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE, CustomRole::MODERATOR_ADVISORY_BOARDS, CustomRole::MODERATOR_ADVISORY_BOARD];
        return $user->hasAnyRole($roles);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateAdvBoard(User $user, Publication $publication)
    {
        $roles = [CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE, CustomRole::MODERATOR_ADVISORY_BOARDS];

        return $user->hasAnyRole($roles)
            || (
            $publication->type = PublicationTypesEnum::TYPE_ADVISORY_BOARD
                && $user->hasAnyRole([CustomRole::MODERATOR_ADVISORY_BOARD])
                && in_array($publication->advisory_boards_id, $user->advisoryBoards ? $user->advisoryBoards->pluck('advisory_board_id')->toArray() : [])
            );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteAdvBoard(User $user, Publication $publication)
    {
        $roles = [CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE, CustomRole::MODERATOR_ADVISORY_BOARDS];

        return $user->hasAnyRole($roles)
            || (
            $publication->type = PublicationTypesEnum::TYPE_ADVISORY_BOARD
                && $user->hasAnyRole([CustomRole::MODERATOR_ADVISORY_BOARD])
                && in_array($publication->advisory_boards_id, $user->advisoryBoards ? $user->advisoryBoards->pluck('advisory_board_id')->toArray() : [])
            );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restoreAdvBoard(User $user, Publication $publication)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDeleteAdvBoard(User $user, Publication $publication)
    {
        return false;
    }

    //    ===========================
    //    OGP
    //     ==========================

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */

    public function viewAnyOgp(User $user)
    {
        return $user->hasAnyRole([CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE, CustomRole::MODERATOR_PARTNERSHIP]);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function createOgp(User $user)
    {
        $roles = [CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE, CustomRole::MODERATOR_PARTNERSHIP];
        return $user->hasAnyRole($roles);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateOgp(User $user, Publication $publication)
    {
        $roles = [CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE, CustomRole::MODERATOR_PARTNERSHIP];

        return $publication->type = PublicationTypesEnum::TYPE_OGP_NEWS && $user->hasAnyRole($roles);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteOgp(User $user, Publication $publication)
    {
        $roles = [CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE, CustomRole::MODERATOR_ADVISORY_BOARDS];

        return $user->hasAnyRole($roles) && $publication->type = PublicationTypesEnum::TYPE_OGP_NEWS;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restoreOgp(User $user, Publication $publication)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDeleteOgp(User $user, Publication $publication)
    {
        return false;
    }
}
