<?php

namespace App\Models;

use App\Enums\DocTypesEnum;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 *
 * @method static where(string $string, int $id)
 * @method static find(mixed $section_id)
 */
class AdvisoryBoardCustom extends ModelActivityExtend
{

    use FilterSort, Translatable;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.advisory_board_custom');
    const TRANSLATABLE_FIELDS = ['body'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;
    public $timestamps = true;

    //activity
    protected string $logName = "advisory_board_custom";

    protected $fillable = ['advisory_board_id', 'title', 'order'];

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->where('code_object', File::CODE_AB)
            ->where('doc_type', DocTypesEnum::AB_CUSTOM_SECTION);
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
            'body' => [
                'type' => 'string',
                'rules' => ['required'],
            ],
        ];
    }
}
