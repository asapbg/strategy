<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;
use illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 */
class LegislativeInitiative extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['description', 'author'];
    const MODULE_NAME = ('custom.nomenclatures.legislative_initiative');
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'legislative_initiative';

    //activity
    protected string $logName = "legislative_initiative";

    protected $fillable = ['regulatory_act_id', 'description', 'author'];

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->name;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'author' => [
                'type' => 'text',
                'rules' => ['required', 'string']
            ],
            'description' => [
                'type' => 'summernote',
                'rules' => ['required', 'string']
            ],
        );
    }

    public function regulatoryAct()
    {
        return $this->hasOne(RegulatoryAct::class, 'id', 'regulatory_act_id');
    }

    public static function optionsList()
    {
        return DB::table('legislative_initiative')
            ->select(['legislative_initiative.id', 'legislative_initiative_translations.name'])
            ->join('legislative_initiative_translations', 'legislative_initiative_translations.legislative_initiative_id', '=', 'legislative_initiative.id')
            ->where('legislative_initiative_translations.locale', '=', app()->getLocale())
            ->orderBy('legislative_initiative_translations.name', 'asc')
            ->get();
    }
}
