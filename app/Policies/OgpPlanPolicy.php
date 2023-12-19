<?php

namespace App\Policies;

use App\Enums\OgpStatusEnum;
use App\Models\OgpPlan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OgpPlanPolicy
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
        return $user->canAny(['manage.*','manage.partnership']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpPlan  $ogpPlan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, OgpPlan $ogpPlan)
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
     * @param  \App\Models\OgpPlan  $ogpPlan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, OgpPlan $ogpPlan)
    {
        return $this->viewAny($user) && ($ogpPlan->status->type == OgpStatusEnum::DRAFT->value || $ogpPlan->status->type == OgpStatusEnum::IN_DEVELOPMENT->value);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpPlan  $ogpPlan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, OgpPlan $ogpPlan)
    {
        return $this->viewAny($user) && ($ogpPlan->status->type == OgpStatusEnum::DRAFT->value || $ogpPlan->status->type == OgpStatusEnum::IN_DEVELOPMENT->value);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpPlan  $ogpPlan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, OgpPlan $ogpPlan)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpPlan  $ogpPlan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, OgpPlan $ogpPlan)
    {
        return false;
    }

    public function newOffer(User $user, OgpPlan $ogpPlan)
    {
        return $ogpPlan->status->type == OgpStatusEnum::IN_DEVELOPMENT->value;
    }

}
