<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'filename', 'content_type', 'ord', 'parent_id', 'version', 'strategic_document_file_id'];

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

    public function documentType(): HasOne
    {
        return $this->hasOne(StrategicDocumentType::class, 'id', 'strategic_document_type_id');
    }

    public function strategicDocument(): HasOne
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

    public static function translationFieldsPropertiesMain(): array
    {
        return array(
            'display_name_main' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:500']
            ],
            'file_info_main' => [
                'type' => 'summernote',
                'rules' => ['nullable', 'string']
            ],
        );
    }

    public static function translationFieldsPropertiesFileEdit(): array
    {
        return array(
            'display_name_file_edit' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:500']
            ],
            'file_info_file_edit' => [
                'type' => 'summernote',
                'rules' => ['nullable', 'string']
            ],
        );
    }

    /**
     * Get the parent document.
     */
    public function parentDocument()
    {
        return $this->belongsTo(StrategicDocumentFile::class, 'parent_id');
    }

    /**
     * Get the child documents.
     */
    public function childDocuments()
    {
        return $this->hasMany(StrategicDocumentFile::class, 'parent_id');
    }

    public function scopeMain($query)
    {
        return $query->where('is_main', 1);
    }

    /**
     * @return string
     */
    public function getDocumentDisplayNameAttribute(): string
    {
        $expiringDate = $this->valid_at == null ? trans('custom.infinite') : Carbon::parse($this->valid_at)->format('d-m-Y');
        if (request()->route()->getName() == 'strategy-document.view') {
            $displayName = '<span class="">' . $this->display_name . '</span>' .
                ' <span class="fw-bold">&#123;</span>' .
                '<span class="valid-date fw-bold"> ' . trans('custom.published_at') . ': ' .
                Carbon::parse($this->document_date_accepted)->format('d-m-Y') . '</span>' .
                ' <span>/</span>' .
                '<span class="valid-date fw-bold"> ' . trans('custom.valid_at') . ': ' .
                $expiringDate . '</span>' .
                ' <span>/</span>' .
                '<span class="str-doc-type fw-bold">' . $this->documentType->name . '</span>' .
                '<span class="fw-bold">&#125;</span>';
        } else {
            $displayName =  $this->display_name . '. {' . trans('custom.published_at') . ' ' .
                Carbon::parse($this->valid_at)->format('d-m-Y') . ' / ' . trans('custom.valid_at') .
                ' ' . $expiringDate . ' / ' . $this->documentType?->name . '}';
        }

        return $displayName;
    }

    /**
     * @return HasMany
     */
    public function versions(): HasMany
    {
        return $this->hasMany(StrategicDocumentFile::class, 'strategic_document_file_id');
    }

    /**
     * Get the latest version of the file.
     *
     * @return HasOne
     */
    public function latestVersion(): HasOne
    {
        return $this->hasOne(StrategicDocumentFile::class, 'strategic_document_file_id')
            ->where('locale', app()->getLocale())
            ->orderByDesc('version');
    }

    /**
     * Get the latest version of the file.
     *
     * @return BelongsTo
     */

    public function parentFile(): BelongsTo
    {
        return $this->belongsTo(StrategicDocumentFile::class, 'strategic_document_file_id')->where('locale', app()->getLocale());
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sys_user');
    }
}
