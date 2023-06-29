<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorityAcceptingStrategicTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'authority_accepting_strategic_id', 'name'];
}
