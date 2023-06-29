<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class AuthorityAdvisoryBoard extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = 'custom.nomenclatures.authority_advisory_board';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'authority_advisory_board';

    //activity
    protected string $logName = "authority_advisory_board";

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
        return DB::table('authority_advisory_board')
            ->select(['authority_advisory_board.id', 'authority_advisory_board_translations.name'])
            ->join('authority_advisory_board_translations', 'authority_advisory_board_translations.consultation_category_id', '=', 'authority_advisory_board.id')
            ->where('authority_advisory_board_translations.locale', '=', app()->getLocale())
            ->orderBy('authority_advisory_board_translations.name', 'asc')
            ->get();
    }
}
