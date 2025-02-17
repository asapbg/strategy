<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategicDocumentChildrenTranslation extends ModelTranslatableActivityExtend
{

    const MODULE_NAME = ('custom.strategic_document_child_translations');
    public $timestamps = false;

    protected $guarded = [];

    protected string $logName = "strategic_document_children_translations";

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? html_entity_decode($value) : $value,
            set: fn (string|null $value) => !empty($value) ?  htmlentities(stripHtmlTags($value, ['p', 'ul', 'ol', 'li', 'b', 'i', 'u', 'a'])) : $value,
        );
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(StrategicDocumentChildren::class, 'id', 'strategic_document_children_id');
    }
}
