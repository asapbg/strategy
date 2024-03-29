<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

class UserChangeRequest extends ModelActivityExtend
{
    const MODULE_NAME = ('custom.user_change_requests');
    public $timestamps = true;

    protected $table = 'user_change_request';

    //activity
    protected string $logName = "user_change_request";

    protected $guarded = [];

    const PENDING = 1;
    const APPROVED = 2;
    const REJECTED = 3;
    const CANCELED = 4;

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
