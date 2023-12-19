<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OgpPlanAreaOfferComment extends ModelActivityExtend
{
    use SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.ogp_plan_area_offer');

    public $timestamps = true;

    protected $table = 'ogp_plan_area_offer_comment';

    //activity
    protected string $logName = "ogp_plan_area_offer_comment";

    protected $fillable = ['ogp_plan_area_offer_id', 'users_id', 'content'];

    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'users_id');
    }

}
