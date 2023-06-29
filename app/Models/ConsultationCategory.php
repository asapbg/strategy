<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class ConsultationCategory extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = 'custom.nomenclatures.consultation_category';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'consultation_category';

    //activity
    protected string $logName = "consultation_category";

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
        return DB::table('consultation_category')
            ->select(['consultation_category.id', 'consultation_category_translations.name'])
            ->join('consultation_category_translations', 'consultation_category_translations.consultation_category_id', '=', 'consultation_category.id')
            ->where('consultation_category_translations.locale', '=', app()->getLocale())
            ->orderBy('consultation_category_translations.name', 'asc')
            ->get();
    }
}
