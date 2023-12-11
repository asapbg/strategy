<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class PCSubject extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['contractor', 'executor', 'objective', 'description'];
    const MODULE_NAME = ('custom.nomenclatures.pc_subject');
    const TYPE_INDIVIDUAL = 1;
    const TYPE_LEGAL_ENTITY = 2;
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'pc_subject';

    //activity
    protected string $logName = "pc_subject";

    protected $fillable = ['type', 'eik', 'contract_date', 'price'];

    public static function getTypes() {
        return collect([
            self::TYPE_INDIVIDUAL => __('custom.type_individual'),
            self::TYPE_LEGAL_ENTITY => __('custom.type_legal_entity'),
        ]);
    }

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->name;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'contractor' => [
                'type' => 'text',
                'rules' => ['required', 'string']
            ],
            'executor' => [
                'type' => 'text',
                'rules' => ['required', 'string']
            ],
            'objective' => [
                'type' => 'text',
                'rules' => ['required', 'string']
            ],
            'description' => [
                'type' => 'textarea',
                'rules' => ['required', 'string']
            ],
        );
    }

    public function consultationLevel()
    {
        return $this->hasOne(ConsultationLevel::class, 'id', 'consultation_level_id');
    }

    public static function optionsList()
    {
        return DB::table('pc_subject')
            ->select(['pc_subject.id', 'pc_subject_translations.name'])
            ->join('pc_subject_translations', 'pc_subject_translations.pc_subject_id', '=', 'pc_subject.id')
            ->where('pc_subject_translations.locale', '=', app()->getLocale())
            ->orderBy('pc_subject_translations.name', 'asc')
            ->get();
    }
}
