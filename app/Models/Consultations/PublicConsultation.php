<?php

namespace App\Models\Consultations;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;
use illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ModelActivityExtend;

class PublicConsultation extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['title', 'description', 'shortTermReason', 'responsibleUnit', 'responsiblePerson'];
    const MODULE_NAME = 'custom.consultations.public_consultation';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'public_consultation';

    //activity
    protected string $logName = "public_consultation";

    protected $fillable = ['consultation_category_id', 'act_type_id', 'program_project_id', 'link_category_id', 'open_from', 'open_to', 'email', 'phone', 'active'];

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
        return DB::table('public_consultation')
            ->select(['public_consultation.id', 'public_consultation_translations.name'])
            ->join('public_consultation_translations', 'public_consultation_translations.public_consultation_id', '=', 'public_consultation.id')
            ->where('public_consultation_translations.locale', '=', app()->getLocale())
            ->orderBy('public_consultation_translations.name', 'asc')
            ->get();
    }
}
