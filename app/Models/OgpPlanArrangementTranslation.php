<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OgpPlanArrangementTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'ogp_plan_arrangement_id', 'name'];
}
