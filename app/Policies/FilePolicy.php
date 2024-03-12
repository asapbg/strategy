<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\File  $file
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function download(User $user, File $file): \Illuminate\Auth\Access\Response|bool
    {
        return $file->code_object == File::CODE_OBJ_PUBLICATION;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\File  $file
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, File $file)
    {
        return ($file->code_object == File::CODE_OBJ_PUBLICATION || $file->code_object == File::CODE_OBJ_PAGE
                || $file->code_object == File::CODE_OBJ_OPERATIONAL_PROGRAM_GENERAL || $file->code_object == File::CODE_OBJ_LEGISLATIVE_PROGRAM_GENERAL
                || $file->code_object == File::CODE_OBJ_STRATEGIC_DOCUMENT || $file->code_object == File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN
                || $file->code_object == File::CODE_OBJ_OGP)
            && $user->user_type == User::USER_TYPE_INTERNAL;
    }
}
