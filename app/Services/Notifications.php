<?php

namespace App\Services;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\AdvisoryBoard;
use App\Models\CustomRole;
use App\Models\User;
use App\Notifications\AdvBoardChanges;
use Illuminate\Support\Facades\Log;

class Notifications
{
    /**
     * Notify all global adb board moderators for changes made it by moderator of specific adv board (role)
     * @param AdvisoryBoard $advisoryBoard
     * @param User $editedBy
     * @param string $section
     * @param array $changes
     * @return void
     */
    public function advChanges(AdvisoryBoard $advisoryBoard, User $editedBy, $section = '', $changes = array()): void
    {
        if ($editedBy->hasRole(CustomRole::MODERATOR_ADVISORY_BOARD)) {

            $data['modelInstance'] = $advisoryBoard;
            $emailJob = new SendSubscribedUserEmailJob($data);
            $moderators = $emailJob->getModerators($advisoryBoard);

            if ($moderators->count()) {
                foreach ($moderators as $user) {

                    try {

                        $user->notify(new AdvBoardChanges($advisoryBoard, $section, $changes));

                        $log_email_subscription = __('notifications_msg.adv_board_changes').': '.$advisoryBoard->name;
                        Log::channel('emails')->info("Send email to moderator ".$user->fullName(). " with email: $user->email, for $log_email_subscription");

                    } catch (\Exception $e) {
                        Log::error('Send notification AdvBoardChanges error: ' . $e);
                    }

                }
            }
        }
    }
}
