<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $locale
 * @property int    $advisory_board_custom_id
 * @property string $title
 * @property string $body
 */
class AdvisoryBoardCustomTranslation extends Model
{

    public $timestamps = false;

    /**
     * Content
     */
    protected function body(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? html_entity_decode($value) : $value,
            set: fn (string|null $value) => !empty($value) ?  htmlentities(stripHtmlTags($value, ['p', 'ul', 'ol', 'li', 'b', 'i', 'u', 'a'])) : $value,
        );
    }
}
