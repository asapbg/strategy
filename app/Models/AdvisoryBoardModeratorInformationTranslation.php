<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

class AdvisoryBoardModeratorInformationTranslation extends ModelActivityExtend
{

    public $timestamps = false;

    /**
     * Content
     */
    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? html_entity_decode($value) : $value,
            set: fn (string|null $value) => !empty($value) ?  htmlentities(stripHtmlTags($value)) : $value,
        );
    }
}
