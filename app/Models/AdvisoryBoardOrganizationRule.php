<?php

namespace App\Models;

use App\Enums\DocTypesEnum;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $advisory_board_id
 */
class AdvisoryBoardOrganizationRule extends ModelActivityExtend
{

    use FilterSort, Translatable;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.advisory_board_regulatory_framework');
    const TRANSLATABLE_FIELDS = ['description'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;
    public $timestamps = true;
    protected $table = 'advisory_board_organization_rules';

    //activity
    protected string $logName = "advisory_board_regulatory_framework";

    protected $fillable = ['advisory_board_id'];

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->where('code_object', File::CODE_AB)
            ->where('doc_type', DocTypesEnum::AB_ORGANIZATION_RULES->value)
            ->orderBy('created_at', 'desc');
    }

    public function siteFiles(): HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->where('code_object', File::CODE_AB)
            ->where('doc_type', DocTypesEnum::AB_ORGANIZATION_RULES->value)
            ->where('parent_id', null)
            ->whereLocale(app()->getLocale())
            ->orderBy('created_at', 'desc');
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
            'description' => [
                'type' => 'summernote',
                'rules' => ['required', 'string'],
                'required_all_lang' => true
            ],
        ];
    }
}
