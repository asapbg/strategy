<?php

namespace App\Models\StrategicDocuments;

use App\Models\ModelActivityExtend;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class Institution extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = 'custom.nomenclatures.institution';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'institution';

    //activity
    protected string $logName = "institution";

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

    public static function optionsList()
    {
        return DB::table('institution')
            ->select(['institution.id', 'institution_translations.name'])
            ->join('institution_translations', 'institution_translations.institution_id', '=', 'institution.id')
            ->where('institution_translations.locale', '=', app()->getLocale())
            ->orderBy('institution_translations.name', 'asc')
            ->get();
    }
}
