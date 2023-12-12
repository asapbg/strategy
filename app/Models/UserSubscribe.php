<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserSubscribe extends ModelActivityExtend
{
    const MODULE_NAME = ('custom.user_subscribes');

    const CONDITION_PUBLISHED = 1;

    const SUBSCRIBED = 1;
    const UNSUBSCRIBED = 0;

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

    /**
     * @return MorphTo
     */
    public function subscribable()
    {
        return $this->morphTo()->withoutGlobalScope(SoftDeletingScope::class);
    }
}
