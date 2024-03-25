<?php

namespace App\Policies;

use App\Enums\PollStatusEnum;
use App\Models\CustomRole;
use App\Models\Poll;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class PollPolicy
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
        return $user->canAny(['manage.*','manage.pools', 'manage.advisory']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Poll  $poll
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Poll $poll)
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
        return $user->canAny(['manage.*','manage.pools', 'manage.advisory']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Poll  $poll
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Poll $poll)
    {
        $pcList = $poll->consultations;
        return !$poll->has_entry
            && (
                $user->hasRole([CustomRole::SUPER_USER_ROLE, CustomRole::ADMIN_USER_ROLE])
                || ( $user->canAny(['manage.pools']) && $user->id == $poll->user_id )
                || ( $user->canAny(['manage.advisory']) && in_array($user->institution_id, $pcList->pluck('importer_institution_id')->toArray()) )
            );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Poll  $poll
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Poll $poll)
    {
        $pcList = $poll->consultations;
        return !$poll->has_entry 
            && is_null($poll->deleted_at)
            && (
                $user->hasRole([CustomRole::SUPER_USER_ROLE, CustomRole::ADMIN_USER_ROLE])
                || ( $user->canAny(['manage.pools']) && $user->id == $poll->user_id )
                || ( $user->canAny(['manage.advisory']) && in_array($user->institution_id, $pcList->pluck('importer_institution_id')->toArray()) )
            );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Poll  $poll
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Poll $poll)
    {
        $pcList = $poll->consultations;
        return !$poll->has_entry && !$pcList->count()
            && !is_null($poll->deleted_at)
            && (
                $user->hasRole([CustomRole::SUPER_USER_ROLE, CustomRole::ADMIN_USER_ROLE])
                || ( $user->canAny(['manage.pools']) && $user->id == $poll->user_id )
                || ( $user->canAny(['manage.advisory']) && in_array($user->institution_id, $pcList->pluck('importer_institution_id')->toArray()) )
            );
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Poll  $poll
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Poll $poll)
    {
        return false;
    }

    /**
     * Determine whether the user can preview result the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Poll  $poll
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function preview(User $user, Poll $poll)
    {
        $pcList = $poll->consultations;
        $isAdmin = $user->hasRole([CustomRole::SUPER_USER_ROLE, CustomRole::ADMIN_USER_ROLE]);
        return (is_null($poll->deleted_at)  && $poll->has_entry)
            && (
                $isAdmin
                || ( $user->canAny(['manage.pools']) && $user->id == $poll->user_id )
                || ( $user->canAny(['manage.advisory'])  && in_array($user->institution_id, $pcList->pluck('importer_institution_id')->toArray()) )
            );
    }

    /**
     * Determine whether the user can send/submit the public model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Poll  $poll
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function send(User $user, Poll $poll)
    {
        return $poll->inPeriod;
    }
}
