<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class StrategicDocumentLevel extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const LEVEL_CENTRAL = 1;
    const LEVEL_AREA = 2;
    const LEVEL_MUNICIPALITY = 3;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = ('custom.nomenclatures.strategic_document_level');
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'strategic_document_level';

    //activity
    protected string $logName = "strategic_document_level";

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
        return DB::table('strategic_document_level')
            ->select(['strategic_document_level.id', 'strategic_document_level_translations.name'])
            ->join('strategic_document_level_translations', 'strategic_document_level_translations.consultation_level_id', '=', 'strategic_document_level.id')
            ->where('strategic_document_level_translations.locale', '=', app()->getLocale())
            ->orderBy('strategic_document_level_translations.name', 'asc')
            ->get();
    }
}
