<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class PolicyArea extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = 'custom.nomenclatures.policy_area';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'policy_area';

    //activity
    protected string $logName = "policy_area";

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
        return DB::table('policy_area')
            ->select(['policy_area.id', 'policy_area_translations.name'])
            ->join('policy_area_translations', 'policy_area_translations.policy_area_id', '=', 'policy_area.id')
            ->where('policy_area_translations.locale', '=', app()->getLocale())
            ->orderBy('policy_area_translations.name', 'asc')
            ->get();
    }
}
