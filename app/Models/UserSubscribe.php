<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscribe extends ModelActivityExtend
{
    const MODULE_NAME = ('custom.user_subscribes');

    const CONDITION_PUBLISHED = 1;

    const CHANNEL_EMAIL = 1;
    const CHANNEL_RSS = 2;

    /**
     * The name of the Model that will be used for activity logs
     *
     * @var string
     */
    protected string $logName = 'user-subscribes';

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
