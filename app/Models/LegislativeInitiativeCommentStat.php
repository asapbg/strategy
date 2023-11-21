<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int  $comment_id
 * @property int  $user_id
 * @property bool $is_like
 */
class LegislativeInitiativeCommentStat extends Model
{

    /**
     * Checks if user voted already.
     *
     * @return bool
     */
    public function hasVoted(): bool
    {
        if (auth()->user()) {
            return $this->likes()->where('user_id', auth()->user()->id)->exists() ||
                $this->dislikes()->where('user_id', auth()->user()->id)->exists();
        }

        return false;
    }
}
