<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class PublicationTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'publication_id', 'title', 'content'];

    /**
     * Content
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? html_entity_decode($value) : $value
        );
    }

    /**
     * Content
     */
    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? html_entity_decode($value) : $value,
            set: fn (string|null $value) => !empty($value) ?  htmlentities(stripHtmlTags($value, ['p', 'ul', 'ol', 'li', 'b', 'i', 'u', 'a'])) : $value,
        );
    }

    /**
     * Content
     */
    protected function shortContent(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? html_entity_decode($value) : $value,
            set: fn (string|null $value) => !empty($value) ?  htmlentities(stripHtmlTags($value, ['p', 'ul', 'ol', 'li', 'b', 'i', 'u', 'a'])) : $value,
        );
    }
}
