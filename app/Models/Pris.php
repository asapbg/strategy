<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pris extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['about', 'legal_reason'];
    const MODULE_NAME = ('custom.pris_documents');
//
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'pris';

    //activity
    protected string $logName = "pris";

    protected $fillable = ['doc_num', 'doc_date', 'legal_act_type_id', 'institution_id', 'version',
        'protocol', 'public_consultation_id', 'newspaper_number', 'active', 'published_at'];

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->title;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'about' => [
                'type' => 'textarea',
                'rules' => ['required', 'string']
            ],
            'legal_reason' => [
                'type' => 'textarea',
                'rules' => ['required', 'string']
            ]
        );
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'pris_tag', 'pris_id', 'tag_id');
    }
}
