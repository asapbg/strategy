<?php

namespace App\Policies;

use App\Models\CustomRole;
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
        return $user->canAny(['manage.*', 'manage.strategic']) && $this->inUserFieldOfAction($user, $file->strategicDocument);
    }

    private function inUserFieldOfAction($user, $item){
        if(!$user){
            return false;
        }

        if (!$user->hasAnyRole([CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE, CustomRole::MODERATOR_STRATEGIC_DOCUMENTS])) {
            $userPolicyAreas = $user->institution ?
                ($user->institution->fieldsOfAction->count() ?
                    $user->institution->fieldsOfAction->pluck('id')->toArray() : [0])
                : [0];
            return in_array($item->policy_area_id, $userPolicyAreas);
        }

        return true;
    }
}
