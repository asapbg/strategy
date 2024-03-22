<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LegislativeInitiative;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class LegislativeInitiativePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->canAny(['manage.*', 'manage.legislative_initiatives']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User                  $user
     * @param LegislativeInitiative $legislative_initiative
     *
     * @return bool
     */
    public function view(User $user, LegislativeInitiative $legislative_initiative): bool
    {
        return $user->canAny(['manage.*', 'manage.legislative_initiatives']);
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
        //return (bool)$user;
        return $user && $user->eauth;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User                  $user
     * @param LegislativeInitiative $legislative_initiative
     *
     * @return bool
     */
    public function update(User $user, LegislativeInitiative $legislative_initiative): bool
    {
        return $user
            && $user->eauth
            && $user->id == $legislative_initiative->author_id
            && $legislative_initiative->getStatus($legislative_initiative->status)->value === \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value;
    }

    /**
     * Determine whether the user can publish the model.
     *
     * @param User                  $user
     * @param LegislativeInitiative $legislative_initiative
     *
     * @return bool
     */
    public function publish(User $user, LegislativeInitiative $legislative_initiative): bool
    {
        return false;
    }

    /**
     * Determine whether the user can unpublished the model.
     *
     * @param User                  $user
     * @param LegislativeInitiative $legislative_initiative
     *
     * @return bool
     */
    public function unPublish(User $user, LegislativeInitiative $legislative_initiative): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User                  $user
     * @param LegislativeInitiative $legislative_initiative
     *
     * @return bool
     */
    public function delete(User $user, LegislativeInitiative $legislative_initiative): bool
    {
        return false;
//        return $user
//            && $user->eauth
//            && $user->id == $legislative_initiative->author_id
//            && $legislative_initiative->getStatus($legislative_initiative->status)->value == \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     *
     * @return bool
     */
    public function restore(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User                  $user
     * @param LegislativeInitiative $legislative_initiative
     *
     * @return bool
     */
    public function forceDelete(User $user, LegislativeInitiative $legislative_initiative): bool
    {
        return false;
    }

    /**
     * Determine whether the user can vote the model.
     *
     * @param User                  $user
     * @param LegislativeInitiative $legislative_initiative
     *
     * @return bool
     */
    public function vote(User $user, LegislativeInitiative $legislative_initiative): bool
    {
        return $user && $legislative_initiative->getStatus($legislative_initiative->status)->value === \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value;
    }

    /**
     * Determine whether the user can comment the model.
     *
     * @param User                  $user
     * @param LegislativeInitiative $legislative_initiative
     *
     * @return bool
     */
    public function comment(User $user, LegislativeInitiative $legislative_initiative): bool
    {
        return $user && $legislative_initiative->getStatus($legislative_initiative->status)->value === \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value;
    }

}
