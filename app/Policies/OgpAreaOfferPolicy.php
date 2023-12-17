<?php

namespace App\Policies;

use App\Models\OgpAreaOffer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OgpAreaOfferPolicy
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
     * @param  \App\Models\OgpAreaOffer  $ogpAreaOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, OgpAreaOffer $ogpAreaOffer)
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
     * @param  \App\Models\OgpAreaOffer  $ogpAreaOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, OgpAreaOffer $ogpAreaOffer)
    {
        return $this->viewAny($user) && $user->id == $ogpAreaOffer->users_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpAreaOffer  $ogpAreaOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, OgpAreaOffer $ogpAreaOffer)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpAreaOffer  $ogpAreaOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, OgpAreaOffer $ogpAreaOffer)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpAreaOffer  $ogpAreaOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, OgpAreaOffer $ogpAreaOffer)
    {
        return false;
    }
}
