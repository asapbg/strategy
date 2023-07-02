<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class AdvisoryActType extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = 'custom.nomenclatures.advisory_act_type';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'advisory_act_type';

    //activity
    protected string $logName = "advisory_act_type";

    protected $fillable = ['consultation_category_id'];

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

    public function consultationCategory()
    {
        return $this->hasOne(ConsultationCategory::class, 'id', 'consultation_category_id');
    }

    public static function optionsList()
    {
        return DB::table('advisory_act_type')
            ->select(['advisory_act_type.id', 'advisory_act_type_translations.name'])
            ->join('advisory_act_type_translations', 'advisory_act_type_translations.advisory_act_type_id', '=', 'advisory_act_type.id')
            ->where('advisory_act_type_translations.locale', '=', app()->getLocale())
            ->orderBy('advisory_act_type_translations.name', 'asc')
            ->get();
    }
}
