<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OgpAreaOfferComment extends ModelActivityExtend
{
    use SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.develop_new_action_plan');

    public $timestamps = true;

    protected $table = 'ogp_area_offer_comment';

    //activity
    protected string $logName = "ogp_area_offer_comment";

    protected $fillable = ['ogp_area_id', 'users_id', 'content'];

    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'users_id');
    }
}
