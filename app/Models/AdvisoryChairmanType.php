<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class AdvisoryChairmanType extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = 'custom.nomenclatures.advisory_chairman_type';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'advisory_chairman_type';

    //activity
    protected string $logName = "advisory_chairman_type";

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
        return DB::table('advisory_chairman_type')
            ->select(['advisory_chairman_type.id', 'advisory_chairman_type_translations.name'])
            ->join('advisory_chairman_type_translations', 'advisory_chairman_type_translations.advisory_chairman_type_id', '=', 'advisory_chairman_type.id')
            ->where('advisory_chairman_type_translations.locale', '=', app()->getLocale())
            ->orderBy('advisory_chairman_type_translations.name', 'asc')
            ->get();
    }
}
