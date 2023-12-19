<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OgpPlanAreaOffer extends ModelActivityExtend
{
    use SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.ogp_plan_area_offer');

    public $timestamps = true;

    protected $table = 'ogp_plan_area_offer';

    //activity
    protected string $logName = "ogp_plan_area_offer";

    protected $fillable = ['ogp_plan_area_id', 'users_id', 'content', 'likes_cnt', 'dislikes_cnt'];

    public function planArea(): HasOne
    {
        return $this->hasOne(OgpPlanArea::class, 'id', 'ogp_plan_area_id');
    }

    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'users_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(OgpPlanAreaOfferComment::class, 'ogp_plan_area_offer_id', 'id');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(OgpPlanAreaOfferVote::class, 'ogp_plan_area_offer_id', 'id');
    }
}
