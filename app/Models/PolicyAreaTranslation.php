<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolicyAreaTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'policy_area_id', 'name'];
}
