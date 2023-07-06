<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class DocumentType extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = 'custom.nomenclatures.document_type';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'document_type';

    //activity
    protected string $logName = "document_type";

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
        return DB::table('document_type')
            ->select(['document_type.id', 'document_type_translations.name'])
            ->join('document_type_translations', 'document_type_translations.document_type_id', '=', 'document_type.id')
            ->where('document_type_translations.locale', '=', app()->getLocale())
            ->orderBy('document_type_translations.name', 'asc')
            ->get();
    }
}
