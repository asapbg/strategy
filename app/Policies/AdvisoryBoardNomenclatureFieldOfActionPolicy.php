<?php

namespace App\Policies;

use App\Models\AdvisoryBoard\AdvisoryBoardNomenclatureFieldOfAction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AdvisoryBoardNomenclatureFieldOfActionPolicy
{

    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->canAny(['manage.*', 'manage.nomenclatures', 'manage.advisory-boards.nomenclatures']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User                                   $user
     * @param AdvisoryBoardNomenclatureFieldOfAction $AdvisoryBoardNomenclatureFieldOfAction
     *
     * @return Response|bool
     */
    public function view(User $user, AdvisoryBoardNomenclatureFieldOfAction $AdvisoryBoardNomenclatureFieldOfAction)
    {
        return $user->canAny(['manage.*', 'manage.nomenclatures', 'manage.advisory-boards.nomenclatures']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->canAny(['manage.*', 'manage.nomenclatures', 'manage.advisory-boards.nomenclatures']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User                                   $user
     * @param AdvisoryBoardNomenclatureFieldOfAction $AdvisoryBoardNomenclatureFieldOfAction
     *
     * @return Response|bool
     */
    public function update(User $user, AdvisoryBoardNomenclatureFieldOfAction $AdvisoryBoardNomenclatureFieldOfAction)
    {
        return $user->canAny(['manage.*', 'manage.nomenclatures', 'manage.advisory-boards.nomenclatures']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User                                   $user
     * @param AdvisoryBoardNomenclatureFieldOfAction $AdvisoryBoardNomenclatureFieldOfAction
     *
     * @return Response|bool
     */
    public function delete(User $user, AdvisoryBoardNomenclatureFieldOfAction $AdvisoryBoardNomenclatureFieldOfAction)
    {
        return $user->canAny(['manage.*', 'manage.nomenclatures', 'manage.advisory-boards.nomenclatures']);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User                                   $user
     * @param AdvisoryBoardNomenclatureFieldOfAction $AdvisoryBoardNomenclatureFieldOfAction
     *
     * @return Response|bool
     */
    public function restore(User $user, AdvisoryBoardNomenclatureFieldOfAction $AdvisoryBoardNomenclatureFieldOfAction)
    {
        return $user->canAny(['manage.*', 'manage.nomenclatures', 'manage.advisory-boards.nomenclatures']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User                                   $user
     * @param AdvisoryBoardNomenclatureFieldOfAction $AdvisoryBoardNomenclatureFieldOfAction
     *
     * @return Response|bool
     */
    public function forceDelete(User $user, AdvisoryBoardNomenclatureFieldOfAction $AdvisoryBoardNomenclatureFieldOfAction)
    {
        //
    }
}
