<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrategicActTypeTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'strategic_act_type_id', 'name'];
}
