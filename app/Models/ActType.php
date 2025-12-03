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
    const MODULE_NAME = ('custom.nomenclatures.act_type');
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'act_type';

    //activity
    protected string $logName = "act_type";

    protected $fillable = ['consultation_level_id'];

    const ACT_LAW = 1;
    const ACT_COUNCIL_OF_MINISTERS = 2; // Акт на Министерския съвет
    const ACT_NON_NORMATIVE_COUNCIL_OF_MINISTERS = 4; //Ненормативен акт (на МС или на министър)
    const ACT_MINISTER = 3; //Акт на министър
    const ACT_FRAME_POSITION = 5; //Акт на друг централен орган
    const ACT_OTHER_CENTRAL_AUTHORITY = 6; //Акт на друг централен орган
    const ACT_REGIONAL_GOVERNOR = 9; //Акт на областен управител
    const ACT_MUNICIPAL = 11; //Акт на общински съвет
    const ACT_MUNICIPAL_MAYOR = 12; //Акт на кмет на община
    const ACT_NON_NORMATIVE = 13; //Ненормативен акт ??????

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
        return DB::table('act_type')
            ->select(['act_type.id', 'act_type_translations.name', 'act_type.consultation_level_id as level'])
            ->join('act_type_translations', 'act_type_translations.act_type_id', '=', 'act_type.id')
            ->where('act_type_translations.locale', '=', app()->getLocale())
            ->where('act_type.active', true)
            ->orderBy('act_type_translations.name', 'asc')
            ->get();
    }
}
