<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OgpPlanTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'ogp_plan_id', 'name', 'content'];
}
