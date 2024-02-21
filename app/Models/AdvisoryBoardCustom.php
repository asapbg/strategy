<?php

namespace App\Models;

use App\Enums\DocTypesEnum;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $advisory_board_id
 * @property int $order
 *
 * @method static find(mixed $section_id)
 */
class AdvisoryBoardCustom extends ModelActivityExtend
{

    use FilterSort, Translatable;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.advisory_board_custom');
    const TRANSLATABLE_FIELDS = ['title', 'body'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;
    public $timestamps = true;

    //activity
    protected string $logName = "advisory_board_custom";

    protected $fillable = ['advisory_board_id', 'order'];

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->where('code_object', File::CODE_AB)
            ->where('doc_type', DocTypesEnum::AB_CUSTOM_SECTION);
    }

    public function siteFiles(): HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->where('code_object', File::CODE_AB)
            ->where('doc_type', DocTypesEnum::AB_CUSTOM_SECTION)
            ->where('parent_id', null)
            ->whereLocale(app()->getLocale());
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
            'title' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:255'],
                'required_all_lang' => true
            ],
            'body' => [
                'type' => 'summernote',
                'rules' => ['required', 'string'],
                'required_all_lang' => true
            ],
        ];
    }
}
