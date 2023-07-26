<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'poll_id', 'title', 'content'];
}
