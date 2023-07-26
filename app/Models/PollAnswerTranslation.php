<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollAnswerTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'poll_answer_id', 'title'];
}
