<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class AuthorityAcceptingStrategic extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = ('custom.nomenclatures.authority_accepting_strategic');
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'authority_accepting_strategic';

    //activity
    protected string $logName = "authority_accepting_strategic";

    protected $fillable = ['nomenclature_level_id'];

    const COUNCIL_MINISTERS = 1;

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
        $q = DB::table('authority_accepting_strategic')
            ->select(['authority_accepting_strategic.id', 'authority_accepting_strategic_translations.name'])
            ->join('authority_accepting_strategic_translations', 'authority_accepting_strategic_translations.authority_accepting_strategic_id', '=', 'authority_accepting_strategic.id')
            ->where('authority_accepting_strategic_translations.locale', '=', app()->getLocale());


        return $q->orderBy('authority_accepting_strategic_translations.name', 'asc')
            ->get();
    }
}
