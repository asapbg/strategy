<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OgpAreaCommitment extends ModelActivityExtend
{
    use SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.develop_new_action_plan');

    public $timestamps = true;

    protected $table = 'ogp_area_commitment';

    //activity
    protected string $logName = "ogp_area_commitment";

    protected $fillable = ['ogp_area_offer_id', 'name'];

    public function offer(): HasOne
    {
        return $this->hasOne(OgpAreaOffer::class, 'id', 'ogp_area_offer_id');
    }

    public function arrangements(): HasMany
    {
        return $this->hasMany(OgpAreaArrangement::class, 'ogp_area_commitment_id', 'id');
    }

}
