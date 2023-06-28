<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class ActType extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = 'custom.nomenclatures.act_type';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'act_type';

    //activity
    protected string $logName = "act_type";

    protected $fillable = ['institution_level_id'];

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

    public function institutionLevel()
    {
        return $this->hasOne(InstitutionLevel::class, 'id', 'institution_level_id');
    }

    public static function optionsList()
    {
        return DB::table('act_types')
            ->select(['act_type.id', 'act_type_translations.name'])
            ->join('act_type_translations', 'act_type_translations.act_type_id', '=', 'act_type.id')
            ->where('act_type_translations.locale', '=', app()->getLocale())
            ->orderBy('act_type_translations.name', 'asc')
            ->get();
    }
}
