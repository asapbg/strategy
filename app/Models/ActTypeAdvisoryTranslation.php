<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActTypeAdvisoryTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'act_type_advisory_id', 'name'];
}
