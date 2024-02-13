<?php

namespace App\Policies;

use App\Models\File;
use App\Models\StrategicDocumentFile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StrategicDocumentFilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StrategicDocumentFile  $file
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, StrategicDocumentFile $file)
    {
        return ($file->strategic_document_type_id == File::CODE_OBJ_STRATEGIC_DOCUMENT)
            && $user->user_type == User::USER_TYPE_INTERNAL;
    }
}
