<?php

namespace App\Policies;

use App\Models\OgpAreaOfferComment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OgpAreaOfferCommentPolicy
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
        return $user->id;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpAreaOfferComment  $ogpAreaOfferComment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, OgpAreaOfferComment $ogpAreaOfferComment)
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpAreaOfferComment  $ogpAreaOfferComment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, OgpAreaOfferComment $ogpAreaOfferComment): \Illuminate\Auth\Access\Response|bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpAreaOfferComment  $ogpAreaOfferComment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, OgpAreaOfferComment $ogpAreaOfferComment): \Illuminate\Auth\Access\Response|bool
    {
        return $user->id == $ogpAreaOfferComment->users_id && $ogpAreaOfferComment->offer->area->status->can_edit;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpAreaOfferComment  $ogpAreaOfferComment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, OgpAreaOfferComment $ogpAreaOfferComment): \Illuminate\Auth\Access\Response|bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpAreaOfferComment  $ogpAreaOfferComment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, OgpAreaOfferComment $ogpAreaOfferComment): \Illuminate\Auth\Access\Response|bool
    {
        return false;
    }
}
