<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OgpAreaOfferVote extends Model
{
    const PAGINATE = 20;
    const MODULE_NAME = ('custom.develop_new_action_plan');

    public $timestamps = true;

    protected $table = 'ogp_area_offer_vote';

    //activity
    protected string $logName = "ogp_area_offer_vote";

    protected $fillable = ['ogp_area_offer_id', 'users_id', 'is_like'];

    public function scopeVoteExits($query, $offerId, $userId)
    {
        return $query->where('ogp_area_offer_id', '=', $offerId)
            ->where('users_id', '=', $userId)
            ->exists();
    }

    public function offer(): HasOne
    {
        return $this->hasOne(OgpAreaOffer::class, 'id', 'ogp_area_offer_id');
    }

    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'users_id');
    }
}