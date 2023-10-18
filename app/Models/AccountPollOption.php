<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountPollOption extends Model
{
    use HasFactory;

    protected $table = 'account_poll_options';
    protected $fillable = ['account_poll_id', 'poll_question_option_id'];
}
