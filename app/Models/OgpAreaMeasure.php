<?php

namespace App\Models;

use App\Traits\FilterSort;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OgpAreaMeasure extends ModelActivityExtend
{
    use FilterSort, SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.develop_new_action_plan');

    public $timestamps = true;

    protected $table = 'ogp_area_measure';

    //activity
    protected string $logName = "ogp_area_measure";

    protected $fillable = ['name', 'content', 'created_at', 'users_id'];


    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'users_id');
    }

    public function measures(): HasMany
    {
        return $this->hasMany(OgpAreaMeasure::class, 'ogp_area_id', 'id');
    }
}
