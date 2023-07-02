<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class ActTypeAdvisory extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = 'custom.nomenclatures.act_type_advisory';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'act_type_advisory';

    //activity
    protected string $logName = "act_type_advisory";

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
        return DB::table('act_type_advisory')
            ->select(['act_type_advisory.id', 'act_type_advisory_translations.name'])
            ->join('act_type_advisory_translations', 'act_type_advisory_translations.act_type_advisory_id', '=', 'act_type_advisory.id')
            ->where('act_type_advisory_translations.locale', '=', app()->getLocale())
            ->orderBy('act_type_advisory_translations.name', 'asc')
            ->get();
    }
}
