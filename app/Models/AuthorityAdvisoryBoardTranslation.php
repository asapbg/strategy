<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorityAdvisoryBoardTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'authority_advisory_board_id', 'name'];
}
