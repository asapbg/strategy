<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class ConsultationLevel extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = 'custom.nomenclatures.consultation_level';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'consultation_level';

    //activity
    protected string $logName = "consultation_level";

    const CENTRAL_LEVEL = 1;

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
        return DB::table('consultation_level')
            ->select(['consultation_level.id', 'consultation_level_translations.name'])
            ->join('consultation_level_translations', 'consultation_level_translations.consultation_level_id', '=', 'consultation_level.id')
            ->where('consultation_level_translations.locale', '=', app()->getLocale())
            ->orderBy('consultation_level_translations.name', 'asc')
            ->get();
    }
}
