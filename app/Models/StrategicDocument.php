<?php

namespace App\Models;

use App\Models\ModelActivityExtend;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;
use illuminate\Database\Eloquent\SoftDeletes;

class StrategicDocument extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['title', 'description'];
    const MODULE_NAME = 'custom.nomenclatures.strategic_document';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'strategic_document';

    //activity
    protected string $logName = "strategic_document";

    protected $fillable = ['strategic_document_level_id', 'policy_area_id', 'strategic_document_type_id', 'strategic_act_type_id', 'document_number', 'authority_accepting_strategic_id', 'document_date', 'consultation_number', 'active'];

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->name;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'title' => [
                'type' => 'text',
                'rules' => ['required', 'string']
            ],
            'description' => [
                'type' => 'ckeditor',
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
        return DB::table('strategic_document')
            ->select(['strategic_document.id', 'strategic_document_translations.name'])
            ->join('strategic_document_translations', 'strategic_document_translations.strategic_document_id', '=', 'strategic_document.id')
            ->where('strategic_document_translations.locale', '=', app()->getLocale())
            ->orderBy('strategic_document_translations.name', 'asc')
            ->get();
    }
}
