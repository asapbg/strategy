<?php

namespace App\Policies;

use App\Models\AdvisoryBoardFunctionFile;
use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AdvisoryBoardFunctionFilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     *
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User                      $user
     * @param \App\Models\AdvisoryBoardFunctionFile $advisoryBoardFunctionFile
     *
     * @return Response|bool
     */
    public function view(User $user, AdvisoryBoardFunctionFile $advisoryBoardFunctionFile)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     *
     * @return Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param File $file
     *
     * @return bool
     */
    public function update(User $user, File $file): bool
    {
        return $user->canAny('manage.*', 'manage.advisory-boards');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User                      $user
     * @param \App\Models\AdvisoryBoardFunctionFile $advisoryBoardFunctionFile
     *
     * @return Response|bool
     */
    public function delete(User $user, AdvisoryBoardFunctionFile $advisoryBoardFunctionFile)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User                      $user
     * @param \App\Models\AdvisoryBoardFunctionFile $advisoryBoardFunctionFile
     *
     * @return Response|bool
     */
    public function restore(User $user, AdvisoryBoardFunctionFile $advisoryBoardFunctionFile)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User                      $user
     * @param \App\Models\AdvisoryBoardFunctionFile $advisoryBoardFunctionFile
     *
     * @return Response|bool
     */
    public function forceDelete(User $user, AdvisoryBoardFunctionFile $advisoryBoardFunctionFile)
    {
        //
    }
}
