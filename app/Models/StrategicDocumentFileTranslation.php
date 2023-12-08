<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ModelActivityExtend;
use Illuminate\Database\Eloquent\SoftDeletes;

class StrategicDocumentFileTranslation extends ModelActivityExtend
{
    use SoftDeletes;

    protected string $logName = "strategic_document_file_translation";
    public $timestamps = false;
    protected $fillable = ['locale', 'strategic_document_file_id', 'display_name', 'file_info'];

    /**
     * Content
     */
    protected function fileInfo(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? html_entity_decode($value) : $value,
            set: fn (string|null $value) => !empty($value) ?  htmlentities(stripHtmlTags($value)) : $value,
        );
    }
}
