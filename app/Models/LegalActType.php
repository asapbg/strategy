<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class LegalActType extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name', 'name_single'];
    const MODULE_NAME = ('custom.nomenclatures.legal_act_type');
    const TYPE_ORDER = 7;
    const TYPE_ARCHIVE = 8;
    const TYPE_DECREES = 1;
    const TYPE_DECISION = 2;
    const TYPE_PROTOCOL_DECISION = 3;
    const TYPE_DISPOSITION = 4;
    const TYPE_PROTOCOL = 5;
    const TYPE_TRANSCRIPTS = 6;

    const IN_PRIS = [self::TYPE_DECREES, self::TYPE_DECISION, self::TYPE_PROTOCOL_DECISION, self::TYPE_DISPOSITION, self::TYPE_PROTOCOL, self::TYPE_TRANSCRIPTS];
    /**
     * 2 - Decision
     * 3 - Protocol Decisions
     *
     * The client only wants these two legal act types to show up in the category selection. The rest are not applicable. */
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'legal_act_type';
    protected $fillable = ['in_pris'];

    //activity
    protected string $logName = "legal_act_type";

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->name;
    }

    public function scopePris($query)
    {
        return $query->where('in_pris', 1);
    }
    public function scopeStrategyCategories($query)
    {
        return $query->whereIn('id', [self::TYPE_DECREES, self::TYPE_PROTOCOL_DECISION, self::TYPE_DECISION]);
    }


    public static function translationFieldsProperties(): array
    {
        return array(
            'name' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:255']
            ],
            'name_single' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:255']
            ],
        );
    }

    public static function optionsList($withoutLaw = true, $withoutArchive = false)
    {
        $q = DB::table('legal_act_type')
            ->select(['legal_act_type.id', 'legal_act_type_translations.name'])
            ->join('legal_act_type_translations', 'legal_act_type_translations.legal_act_type_id', '=', 'legal_act_type.id')
            ->where('legal_act_type_translations.locale', '=', app()->getLocale())
            ->where('legal_act_type.in_pris','=', 1);
        if($withoutLaw) {
            $q->where('legal_act_type.id', '<>', LegalActType::TYPE_ORDER);
        }

        return $q->orderBy('legal_act_type_translations.name', 'asc')
            ->get();
    }
}
