<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

class OgpPlanScheduleTranslation extends ModelTranslatableActivityExtend
{

    public $timestamps = false;

    protected $guarded = [];

    protected string $logName = "ogp_plan_schedule_translations";


    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? html_entity_decode($value) : $value
        );
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? html_entity_decode($value) : $value,
            set: fn (string|null $value) => !empty($value) ?  htmlentities(stripHtmlTags($value)) : $value,
        );
    }
}
