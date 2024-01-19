<?php

namespace App\Services;

use App\Models\AdvisoryBoard;
use App\Models\CustomRole;
use App\Models\User;
use App\Notifications\AdvBoardChanges;
use Illuminate\Support\Facades\Log;

class Notifications
{
    /**
     * Notify all global adb board moderators for changes made it by moderator of specific adv board (role)
     * @param AdvisoryBoard $item
     * @param User $editedBy
     * @return void
     */
    public function advChanges(AdvisoryBoard $item, User $editedBy): void
    {
        if($editedBy->hasRole(CustomRole::MODERATOR_ADVISORY_BOARD)){
            $moderators = User::whereHas('roles', function($q){
                $q->where("name", CustomRole::MODERATOR_ADVISORY_BOARDS);
            })->get();

            if($moderators->count()){
                foreach ($moderators as $user){
                    try {
                        $user->notify(new AdvBoardChanges($item));
                    } catch (\Exception $e){
                        Log::error('Send notification AdvBoardChanges error: '.$e);
                    }
                }
            }
        }
    }
}
