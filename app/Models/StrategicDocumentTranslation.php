<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class StrategicDocumentTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'strategic_document_id', 'title', 'description'];
    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? html_entity_decode($value) : $value,
            set: fn (string|null $value) => !empty($value) ?  htmlentities(stripHtmlTags($value, ['p', 'ul', 'ol', 'li', 'b', 'i', 'u', 'a'])) : $value,
        );
    }

    /**
     * Content
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? html_entity_decode($value) : $value
        );
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(StrategicDocument::class, 'id', 'strategic_document_id');
    }
}
