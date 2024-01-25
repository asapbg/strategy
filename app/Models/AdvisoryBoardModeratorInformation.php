<?php

namespace App\Models;

use App\Enums\DocTypesEnum;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdvisoryBoardModeratorInformation extends ModelActivityExtend
{

    use FilterSort, Translatable;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.advisory_board_moderator_information');
    const TRANSLATABLE_FIELDS = ['description'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;
    public $timestamps = true;

    //activity
    protected string $logName = "advisory_board_moderator_information";

    protected $fillable = ['advisory_board_id'];

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->where(['code_object' => File::CODE_OBJ_AB_MODERATOR, 'doc_type' => DocTypesEnum::AB_MODERATOR->value]);
    }

    public function filesByLocale(): HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->where(['code_object' => File::CODE_OBJ_AB_MODERATOR, 'doc_type' => DocTypesEnum::AB_MODERATOR->value])
            ->where('locale', '=', app()->getLocale());
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
                'required_all_lang' => false
            ],
        ];
    }
}
