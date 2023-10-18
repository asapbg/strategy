<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class RegulatoryAct extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name', 'institution'];
    const MODULE_NAME = ('custom.nomenclatures.regulatory_act');
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'regulatory_act';

    //activity
    protected string $logName = "regulatory_act";

    protected $fillable = ['regulatory_act_type_id', 'number'];

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
            'institution' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:255']
            ],
        );
    }

    public function regulatoryActType()
    {
        return $this->hasOne(RegulatoryActType::class, 'id', 'regulatory_act_type_id');
    }

    public static function optionsList()
    {
        return DB::table('regulatory_act')
            ->select(['regulatory_act.id', 'regulatory_act_translations.name'])
            ->join('regulatory_act_translations', 'regulatory_act_translations.regulatory_act_id', '=', 'regulatory_act.id')
            ->where('regulatory_act_translations.locale', '=', app()->getLocale())
            ->orderBy('regulatory_act_translations.name', 'asc')
            ->get();
    }
}
