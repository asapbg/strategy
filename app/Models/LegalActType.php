<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class LegalActType extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = ('custom.nomenclatures.legal_act_type');
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'legal_act_type';

    //activity
    protected string $logName = "legal_act_type";

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
        return DB::table('legal_act_type')
            ->select(['legal_act_type.id', 'legal_act_type_translations.name'])
            ->join('legal_act_type_translations', 'legal_act_type_translations.legal_act_type_id', '=', 'legal_act_type.id')
            ->where('legal_act_type_translations.locale', '=', app()->getLocale())
            ->orderBy('legal_act_type_translations.name', 'asc')
            ->get();
    }
}
