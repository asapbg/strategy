<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Translatable;

/**
 * @property int $id
 * @property int $advisory_board_id
 */
class AdvisoryBoardRegulatoryFramework extends ModelActivityExtend
{

    use FilterSort, Translatable;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.advisory_board_regulatory_framework');
    const TRANSLATABLE_FIELDS = ['description'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;
    public $timestamps = true;

    //activity
    protected string $logName = "advisory_board_regulatory_framework";

    protected $fillable = ['advisory_board_id'];

    public function allFiles(): HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->withTrashed()
            ->where('code_object', File::CODE_AB)
            ->where('doc_type', DocTypesEnum::AB_SECRETARIAT->value);
    }

    public function siteFiles(): HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->where('code_object', File::CODE_AB)
            ->where('doc_type', DocTypesEnum::AB_SECRETARIAT->value)
            ->where('parent_id', null);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->where('code_object', File::CODE_AB)
            ->where('doc_type', DocTypesEnum::AB_SECRETARIAT->value);
    }

    /**
     * Get the model name
     */
    public function getModelName()
    {
        return $this->name;
    }

    public static function translationFieldsProperties(): array
    {
        return [
            'framework_description' => [
                'type' => 'string',
                'rules' => ['required'],
            ],
        ];
    }
}
