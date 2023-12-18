<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OgpAreaOffer extends ModelActivityExtend
{
    use SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.develop_new_action_plan');

    public $timestamps = true;

    protected $table = 'ogp_area_offer';

    //activity
    protected string $logName = "ogp_area_offer";

    protected $fillable = ['ogp_area_id', 'users_id', 'likes_cnt', 'dislikes_cnt'];

    public function area(): HasOne
    {
        return $this->hasOne(OgpArea::class, 'id', 'ogp_area_id');
    }

    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'users_id');
    }

    public function commitments(): HasMany
    {
        return $this->hasMany(OgpAreaCommitment::class, 'ogp_area_offer_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(OgpAreaOfferComment::class, 'ogp_area_offer_id', 'id');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(OgpAreaOfferVote::class, 'ogp_area_offer_id', 'id');
    }
}
