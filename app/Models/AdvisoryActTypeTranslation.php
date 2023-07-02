<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvisoryActTypeTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'advisory_act_type_id', 'name'];
}
