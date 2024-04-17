<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int  $user_id
 * @property int  $advisory_board_id
 * @property User $user
 */
class AdvisoryBoardModerator extends ModelActivityExtend
{

    const MODULE_NAME = ('custom.adv_board_moderators');
    protected $fillable = ['advisory_board_id', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function board()
    {
        return $this->hasOne(AdvisoryBoard::class, 'id', 'advisory_board_id');
    }
}
