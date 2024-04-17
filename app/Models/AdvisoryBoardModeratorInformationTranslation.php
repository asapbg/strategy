<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

class AdvisoryBoardModeratorInformationTranslation extends ModelTranslatableActivityExtend
{

    const MODULE_NAME = ('custom.adv_board_moderator_translations');
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
