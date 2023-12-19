<?php

namespace App\Policies;

use App\Enums\OgpStatusEnum;
use App\Models\OgpArea;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OgpAreaPolicy
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
     * @param  \App\Models\OgpArea  $ogpArea
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, OgpArea $ogpArea)
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
     * @param  \App\Models\OgpArea  $ogpArea
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, OgpArea $ogpArea)
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpArea  $ogpArea
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, OgpArea $ogpArea)
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpArea  $ogpArea
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, OgpArea $ogpArea)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OgpArea  $ogpArea
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, OgpArea $ogpArea): \Illuminate\Auth\Access\Response|bool
    {
        return false;
    }

    public function createOffer(User $user, OgpArea $ogpArea): bool
    {
        return $user->id && $ogpArea->status->type == OgpStatusEnum::IN_DEVELOPMENT->value;
    }
}
