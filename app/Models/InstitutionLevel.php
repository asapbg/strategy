<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class InstitutionLevel extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = 'custom.nomenclatures.institution_level';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'institution_level';

    //activity
    protected string $logName = "institution_level";

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->name;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'name' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:255']
            ],
        );
    }

    public static function optionsList()
    {
        return DB::table('institution_levels')
            ->select(['institution_level.id', 'institution_level_translations.name'])
            ->join('institution_level_translations', 'institution_level_translations.institution_level_id', '=', 'institution_level.id')
            ->where('institution_level_translations.locale', '=', app()->getLocale())
            ->orderBy('institution_level_translations.name', 'asc')
            ->get();
    }
}
