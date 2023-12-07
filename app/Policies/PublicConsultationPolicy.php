<?php

namespace App\Policies;

use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class PublicConsultationPolicy
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
        return $user->canAny(['manage.*', 'manage.advisory']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\PublicConsultation  $publicConsultation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PublicConsultation $publicConsultation)
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
        return $user->canAny(['manage.*', 'manage.advisory']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\PublicConsultation  $publicConsultation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PublicConsultation $publicConsultation)
    {
        return $user->canAny(['manage.*', 'manage.advisory']) &&
            ($user->hasRole([CustomRole::SUPER_USER_ROLE, CustomRole::ADMIN_USER_ROLE]) || $user->institution_id == $publicConsultation->importer_institution_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\PublicConsultation  $publicConsultation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PublicConsultation $publicConsultation)
    {
        return $user->canAny(['manage.*', 'manage.advisory.delete']) &&
            ($user->hasRole([CustomRole::SUPER_USER_ROLE, CustomRole::ADMIN_USER_ROLE]) || $user->institution_id == $publicConsultation->importer_institution_id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\PublicConsultation  $publicConsultation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PublicConsultation $publicConsultation)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\PublicConsultation  $publicConsultation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PublicConsultation $publicConsultation)
    {
        return false;
    }

    /**
     * Determine whether the user can comment the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\PublicConsultation  $publicConsultation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function comment(User $user, PublicConsultation $publicConsultation)
    {
        return true;
    }

    /**
     * Determine whether the user can comment the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Consultations\PublicConsultation  $publicConsultation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function proposalReport(User $user, PublicConsultation $publicConsultation)
    {
        return $user->canAny(['manage.*', 'manage.advisory']) &&
            ($user->hasRole([CustomRole::SUPER_USER_ROLE, CustomRole::ADMIN_USER_ROLE]) || $user->institution_id == $publicConsultation->importer_institution_id);
    }

    /**
     * Determine whether the user can publish the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Consultations\PublicConsultation $publicConsultation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function publish(User $user, PublicConsultation $publicConsultation)
    {
        return $user->canAny(['manage.*', 'manage.advisory']) && !$publicConsultation->active;
    }

    /**
     * Determine whether the user can unpublish the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Consultations\PublicConsultation $publicConsultation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function unPublish(User $user, PublicConsultation $publicConsultation)
    {
        return $user->canAny(['manage.*', 'manage.advisory']) && $publicConsultation->active;
    }
}
