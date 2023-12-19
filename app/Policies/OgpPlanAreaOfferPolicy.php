<?php

namespace App\Policies;

use App\Models\OgpPlanAreaOffer;
use App\Models\OgpPlanAreaOfferVote;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OgpPlanAreaOfferPolicy
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
     * @param  \App\Models\OgpPlanAreaOffer  $ogpPlanAreaOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, OgpPlanAreaOffer $ogpPlanAreaOffer)
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
     * @param  \App\Models\OgpPlanAreaOffer  $ogpPlanAreaOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, OgpPlanAreaOffer $ogpPlanAreaOffer)
    {
        return $this->viewAny($user) && $user->id == $ogpPlanAreaOffer->users_id && $ogpPlanAreaOffer->planArea->plan->status->can_edit;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpPlanAreaOffer  $ogpPlanAreaOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, OgpPlanAreaOffer $ogpPlanAreaOffer)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpPlanAreaOffer  $ogpPlanAreaOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, OgpPlanAreaOffer $ogpPlanAreaOffer)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpPlanAreaOffer  $ogpPlanAreaOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, OgpPlanAreaOffer $ogpPlanAreaOffer)
    {
        return false;
    }

    /**
     * @param User $user
     * @param OgpPlanAreaOffer $ogpAreaOffer
     * @return bool
     */
    public function createComment(User $user, OgpPlanAreaOffer $ogpAreaOffer): bool
    {
        return $ogpAreaOffer->planArea->plan->status->can_edit;
    }

    /**
     * @param User $user
     * @param OgpPlanAreaOffer $ogpAreaOffer
     * @return bool
     */
    public function vote(User $user, OgpPlanAreaOffer $ogpAreaOffer): bool
    {
        $exits = OgpPlanAreaOfferVote::VoteExits($ogpAreaOffer->id, $user->id);
        return !$exits;
    }
}
