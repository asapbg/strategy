<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OgpPlanArea extends ModelActivityExtend
{
    use SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.ogp_plans_area');

    public $timestamps = false;

    protected $table = 'ogp_plan_area';

    //activity
    protected string $logName = "ogp_plan_area";

    protected $fillable = ['ogp_plan_id', 'ogp_area_id'];

    public function plan(): HasOne
    {
        return $this->hasOne(OgpPlan::class, 'id', 'ogp_plan_id');
    }

    public function area(): HasOne
    {
        return $this->hasOne(OgpArea::class, 'id', 'ogp_area_id');
    }

    public function arrangements(): HasMany
    {
        return $this->hasMany(OgpPlanArrangement::class, 'ogp_plan_area_id', 'id');
    }

    public function offers(): HasMany
    {
        return $this->hasMany(OgpPlanAreaOffer::class, 'ogp_plan_area_id', 'id');
    }
}
