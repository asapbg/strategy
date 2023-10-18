<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class ConsultationType extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = ('custom.nomenclatures.consultation_type');
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'consultation_type';

    //activity
    protected string $logName = "consultation_type";

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
        return DB::table('consultation_type')
            ->select(['consultation_type.id', 'consultation_type_translations.name'])
            ->join('consultation_type_translations', 'consultation_type_translations.consultation_type_id', '=', 'consultation_type.id')
            ->where('consultation_type_translations.locale', '=', app()->getLocale())
            ->orderBy('consultation_type_translations.name', 'asc')
            ->get();
    }
}
