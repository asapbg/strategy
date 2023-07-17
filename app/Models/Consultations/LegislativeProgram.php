<?php

namespace App\Models\Consultations;

use App\Models\ModelActivityExtend;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class LegislativeProgram extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['title', 'description'];
    const MODULE_NAME = 'custom.nomenclatures.legislative_program';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'legislative_program';

    //activity
    protected string $logName = "legislative_program";

    protected $fillable = ['effective_from', 'effective_to'];

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->title;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'title' => [
                'type' => 'textarea',
                'rules' => ['required', 'string']
            ],
            'description' => [
                'type' => 'ckeditor',
                'rules' => ['required', 'string']
            ],
        );
    }

    public static function optionsList()
    {
        return DB::table('legislative_program')
            ->select(['legislative_program.id', 'institution_translations.name'])
            ->join('institution_translations', 'institution_translations.institution_id', '=', 'legislative_program.id')
            ->where('institution_translations.locale', '=', app()->getLocale())
            ->orderBy('institution_translations.name', 'asc')
            ->get();
    }
}
