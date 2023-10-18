<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class RegulatoryActType extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = ('custom.nomenclatures.regulatory_act_type');
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'regulatory_act_type';

    //activity
    protected string $logName = "regulatory_act_type";

    protected $fillable = ['consultation_level_id'];

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

    public function consultationLevel()
    {
        return $this->hasOne(ConsultationLevel::class, 'id', 'consultation_level_id');
    }

    public static function optionsList()
    {
        return DB::table('regulatory_act_type')
            ->select(['regulatory_act_type.id', 'regulatory_act_type_translations.name'])
            ->join('regulatory_act_type_translations', 'regulatory_act_type_translations.regulatory_act_type_id', '=', 'regulatory_act_type.id')
            ->where('regulatory_act_type_translations.locale', '=', app()->getLocale())
            ->orderBy('regulatory_act_type_translations.name', 'asc')
            ->get();
    }
}
