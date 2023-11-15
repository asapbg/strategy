<?php

namespace App\Models;

use App\Models\ModelActivityExtend;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use illuminate\Database\Eloquent\SoftDeletes;

class StrategicDocument extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['title', 'description'];
    const MODULE_NAME = ('custom.strategic_documents');
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'strategic_document';

    //activity
    protected string $logName = "strategic_document";

    protected $fillable = ['strategic_document_level_id', 'policy_area_id', 'strategic_document_type_id',
        'strategic_act_type_id', 'strategic_act_number', 'strategic_act_link', 'accept_act_institution_type_id',
        'pris_act_id', 'document_date', 'public_consultation_id', 'active'];

    /**
     * Dates
     */
    protected function documentDate(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? displayDate($value) : '',
            set: fn (string|null $value) => !empty($value) ?  databaseDate($value) : null
        );
    }

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->title;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'title' => [
                'type' => 'text',
                'rules' => ['required', 'string']
            ],
            'description' => [
                'type' => 'summernote',
                'rules' => ['required', 'string']
            ],
        );
    }

    public function files()
    {
        return $this->hasMany(StrategicDocumentFile::class, 'strategic_document_id', 'id')->orderBy('ord');
    }

    public function documentLevel(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(StrategicDocumentLevel::class, 'id', 'strategic_document_level_id');
    }

    public function acceptActInstitution(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AuthorityAcceptingStrategic::class, 'id', 'accept_act_institution_type_id');
    }

    public function documentType(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(StrategicDocumentType::class, 'id', 'strategic_document_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function policyArea(): BelongsTo
    {
        return $this->belongsTo(PolicyArea::class);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('active', true);
    }
}
