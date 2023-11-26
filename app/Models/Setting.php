<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends ModelActivityExtend
{
    use SoftDeletes;
    const MODULE_NAME = ('custom.setting');
    protected $guarded = [];
    public $timestamps = true;

//    const SESSION_LIMIT_KEY = 'session_time_limit';
    const PAGE_CONTENT_OP = 'op_text';
    const PAGE_CONTENT_LP = 'lp_text';

    //activity
    protected string $logName = "settings";

    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? html_entity_decode($value) : $value,
            set: fn (string|null $value) => !empty($value) ?  htmlentities(stripHtmlTags($value)) : $value,
        );
    }
    public function scopeEditable($query)
    {
        $query->where('settings.editable', 1);
    }
}
