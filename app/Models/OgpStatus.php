<?php

namespace App\Models;

use App\Enums\OgpStatusEnum;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class OgpStatus extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.develop_new_action_plan');
    const TRANSLATABLE_FIELDS = ['name'];

    public $timestamps = true;

    protected $table = 'ogp_status';

    //activity
    protected string $logName = "ogp_status";

    protected $fillable = ['active', 'css_class', 'can_edit'];
    protected $translatedAttributes = OgpStatus::TRANSLATABLE_FIELDS;

    public function scopeActive($query)
    {
        return $query->where('active', '=', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('type', '=', OgpStatusEnum::DRAFT->value);
    }
    public function scopeInDevelopment($query)
    {
        return $query->where('type', '=', OgpStatusEnum::IN_DEVELOPMENT->value);
    }
    public function scopeFinal($query)
    {
        return $query->where('type', '=', OgpStatusEnum::FINAL->value);
    }
    public function scopeActiveStatus($query)
    {
        return $query->where('type', '=', OgpStatusEnum::ACTIVE->value);
    }
}
