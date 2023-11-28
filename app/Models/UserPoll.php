<?php

namespace App\Models;

use App\Traits\FilterSort;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPoll extends Model
{
    use FilterSort, SoftDeletes;

    public $timestamps = true;
    protected $table = 'user_poll';
    protected $guarded = [];

    public function answers(){
        return $this->belongsToMany(PollQuestionOption::class, 'user_poll_option', 'user_poll_id');
    }

}
