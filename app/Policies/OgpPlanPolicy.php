<?php

namespace App\Policies;

use App\Enums\OgpStatusEnum;
use App\Models\OgpPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;
use function Clue\StreamFilter\fun;

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
        return $user->canAny(['manage.*','manage.partnership'])  && $ogpPlan->national_plan;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->canAny(['manage.*','manage.partnership']);
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
        return $user->canAny(['manage.*','manage.partnership']) && $ogpPlan->national_plan;
            //&& ($ogpPlan->status->type != OgpStatusEnum::ACTIVE->value || Carbon::parse($ogpPlan->to_date)->format('Y-m-d') < Carbon::now()->format('Y-m-d'));
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
        return $user->canAny(['manage.*','manage.partnership']) && $ogpPlan->national_plan && $ogpPlan->status->type != OgpStatusEnum::ACTIVE->value;
    }


    public function deleteArea(User $user, OgpPlan $ogpPlan)
    {
        return $user->canAny(['manage.*','manage.partnership']) && $ogpPlan->national_plan && $ogpPlan->status->type != OgpStatusEnum::ACTIVE->value;
    }

    public function deleteArrangement(User $user, OgpPlan $ogpPlan)
    {
        return $user->canAny(['manage.*','manage.partnership']) && $ogpPlan->national_plan && $ogpPlan->status->type != OgpStatusEnum::ACTIVE->value;
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
        return !$ogpPlan->national_plan && $ogpPlan->status->type == OgpStatusEnum::IN_DEVELOPMENT->value
            && dateBetween($ogpPlan->from_date_develop, $ogpPlan->to_date_develop);
    }

    public function commentDevelopPlan(User $user, OgpPlan $ogpPlan)
    {
        return !$ogpPlan->national_plan && $ogpPlan->status->type == OgpStatusEnum::IN_DEVELOPMENT->value;
    }


//    Develop plan policy

    public function viewDevelopPlan(User $user, OgpPlan $ogpPlan): bool
    {
        return $user->canAny(['manage.*','manage.partnership'])
            && !$ogpPlan->national_plan
            && (in_array($ogpPlan->status->type, [OgpStatusEnum::FINAL->value]));
    }

    public function createDevelopPlan(User $user): \Illuminate\Auth\Access\Response|bool
    {
        return $user->canAny(['manage.*','manage.partnership'])
            && !OgpPlan::NotNational()->whereHas('status', function ($q){
                $q->whereIn('ogp_status.type', [OgpStatusEnum::IN_DEVELOPMENT->value]);
            })->get()->count();
    }

    public function updateDevelopPlan(User $user, OgpPlan $ogpPlan): bool
    {
        return $user->canAny(['manage.*','manage.partnership'])
            && !$ogpPlan->national_plan
            && (in_array($ogpPlan->status->type, [OgpStatusEnum::DRAFT->value, OgpStatusEnum::IN_DEVELOPMENT->value]));
    }

    public function deleteDevelopPlan(User $user, OgpPlan $ogpPlan): bool
    {
        return $user->canAny(['manage.*','manage.partnership'])
            && !$ogpPlan->national_plan
            && (in_array($ogpPlan->status->type, [OgpStatusEnum::DRAFT->value, OgpStatusEnum::IN_DEVELOPMENT->value]));
    }

    public function deleteDevelopArea(User $user, OgpPlan $ogpPlan)
    {
        return $user->canAny(['manage.*','manage.partnership']) && !$ogpPlan->national_plan
            && (in_array($ogpPlan->status->type, [OgpStatusEnum::DRAFT->value, OgpStatusEnum::IN_DEVELOPMENT->value]));
    }


    public function viewPublic(User $user, OgpPlan $ogpPlan)
    {
        return $user->id && (
                $ogpPlan->status->type == OgpStatusEnum::IN_DEVELOPMENT->value
                || ($ogpPlan->status->type != OgpStatusEnum::DRAFT->value && $ogpPlan->has('plan'))
            );
    }

}
