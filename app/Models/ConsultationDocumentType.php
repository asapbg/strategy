<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class ConsultationDocumentType extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = 'custom.nomenclatures.consultation_document_type';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'consultation_document_type';

    //activity
    protected string $logName = "consultation_document_type";

    protected $fillable = ['consultation_level_id', 'act_type_id'];

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

    public function actType()
    {
        return $this->hasOne(ActType::class, 'id', 'act_type_id');
    }

    public static function optionsList()
    {
        return DB::table('consultation_document_type')
            ->select(['consultation_document_type.id', 'consultation_document_type_translations.name'])
            ->join('consultation_document_type_translations', 'consultation_document_type_translations.consultation_document_type_id', '=', 'consultation_document_type.id')
            ->where('consultation_document_type_translations.locale', '=', app()->getLocale())
            ->orderBy('consultation_document_type_translations.name', 'asc')
            ->get();
    }
}
