<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActTypeTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'act_type_id', 'name'];
}
