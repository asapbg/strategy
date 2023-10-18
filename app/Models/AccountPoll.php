<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountPoll extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'account_polls';
    public $timestamps = true;
    protected $guarded = [];

    public function options(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(PollQuestionOption::class, 'user_poll_options', 'user_poll_id');
    }
}

