<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class StrategicDocumentFile extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const TRANSLATABLE_FIELDS = ['display_name', 'file_info'];
    const MODULE_NAME = ('custom.strategic_document_files');
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'strategic_document_file';

    //activity
    protected string $logName = "strategic_document_file";

    protected $fillable = ['strategic_document_id', 'strategic_document_type_id', 'valid_at',
        'visible_in_report', 'sys_user', 'path', 'file_text',
        'filename', 'content_type', 'ord'];

    const DIR_PATH = 'strategic_doc'.DIRECTORY_SEPARATOR;

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->display_name;
    }

    /**
     * Dates
     */
    protected function validAt(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? displayDate($value) : '',
            set: fn (string|null $value) => !empty($value) ?  databaseDate($value) : null,
        );
    }

    public function documentType(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(StrategicDocumentType::class, 'id', 'strategic_document_type_id');
    }

    public function strategicDocument(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(StrategicDocument::class, 'id', 'strategic_document_id');
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'display_name' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:500']
            ],
            'file_info' => [
                'type' => 'summernote',
                'rules' => ['nullable', 'string']
            ],
        );
    }
}
