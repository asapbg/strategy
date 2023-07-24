<?php

namespace App\Models;

use App\Models\ModelActivityExtend;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;
use illuminate\Database\Eloquent\SoftDeletes;

class Link extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['title', 'text'];
    const MODULE_NAME = 'custom.nomenclatures.link';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'link';

    //activity
    protected string $logName = "link";

    protected $fillable = ['link_category_id', 'url', 'active'];

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
            'text' => [
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
        return DB::table('link')
            ->select(['link.id', 'link_translations.name'])
            ->join('link_translations', 'link_translations.link_id', '=', 'link.id')
            ->where('link_translations.locale', '=', app()->getLocale())
            ->orderBy('link_translations.name', 'asc')
            ->get();
    }
}
