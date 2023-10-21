<?php

namespace App\Policies;

use App\Models\StrategicDocument;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StrategicDocumentPolicy
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
        return $user->canAny(['manage.*', 'manage.strategic']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StrategicDocument  $strategicDocument
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, StrategicDocument $strategicDocument)
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
        return $user->canAny(['manage.*', 'manage.strategic']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StrategicDocument  $strategicDocument
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, StrategicDocument $strategicDocument)
    {
        //TODO add more scopes
        return $user->canAny(['manage.*', 'manage.strategic']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StrategicDocument  $strategicDocument
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, StrategicDocument $strategicDocument)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StrategicDocument  $strategicDocument
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, StrategicDocument $strategicDocument)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StrategicDocument  $strategicDocument
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, StrategicDocument $strategicDocument)
    {
        return false;
    }
}
