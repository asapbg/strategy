<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OgpAreaArrangementField extends ModelActivityExtend
{
    use SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.develop_new_action_plan');

    public $timestamps = true;

    protected $table = 'ogp_area_arrangement_field';

    //activity
    protected string $logName = "ogp_area_arrangement_field";

    protected $fillable = ['ogp_area_arrangement_id', 'name', 'content', 'is_system'];

    public function arrangement(): HasOne
    {
        return $this->hasOne(OgpAreaArrangement::class, 'id', 'ogp_area_arrangement_id');
    }
}
