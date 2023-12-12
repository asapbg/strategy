<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Models\User;
use App\Models\UserSubscribe;

class CommentsObserver
{
    /**
     * Handle the Comments "created" event.
     *
     * @param Comments $comment
     * @return void
     */
    public function created(Comments $comment)
    {
        if ($comment->object_code == Comments::PC_OBJ_CODE) {
            $this->sendEmails($comment);
        }
    }

    /**
     * Handle the Comments "updated" event.
     *
     * @param Comments $comment
     * @return void
     */
    public function updated(Comments $comment)
    {
        //
    }


    /**
     * Send emails to all administrators, moderators and subscribed users
     *
     * @param Comments $comment
     * @return void
     */
    private function sendEmails(Comments $comment): void
    {
        $publicConsultation = $comment->commented;
        $moderator_roles = CustomRole::select('name')
            ->where('name', 'ILIKE', 'moderator-%')
            ->get()
            ->pluck('name')
            ->toArray();
        $moderators = User::whereActive(true)
            ->hasRole($moderator_roles)
            ->where('institution_id', $publicConsultation->importer_institution_id)
            ->get()
            ->unique('id');
        $subscribedUsers = UserSubscribe::select('user_subscribes.*')
            ->where('subscribable_type', PublicConsultation::class)
            ->join('comments', 'comments.user_id', '=', 'user_subscribes.user_id')
            ->where('object_code', Comments::PC_OBJ_CODE)
            ->where('comments.object_id', $publicConsultation->id)
            ->where('comments.id', '!=', $comment->id)
            ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
            ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
            ->where('is_subscribed', UserSubscribe::SUBSCRIBED)
            ->get()
            ->unique('user_id');

        if (!$moderators && $subscribedUsers->count() == 0) {
            return;
        }

        $data['event'] = 'new-comment';
        $data['moderators'] = $moderators;
        $data['subscribedUsers'] = $subscribedUsers;
        $data['modelInstance'] = $publicConsultation;
        $data['markdown'] = 'public-consultation';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}
