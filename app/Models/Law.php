<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class Law extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];

    const MODULE_NAME = ('custom.laws');
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'law';

    //activity
    protected string $logName = "laws";

    protected $fillable = ['active'];

    public function getModelName() {
        return $this->title;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'name' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:1000']
            ],
        );
    }

    public function scopeActive($query){
        $query->where('law.active', 1);
    }

    public static function optionsList($active = false)
    {
        $q = DB::table('law')
            ->select(['law.id', 'law_translations.name'])
            ->join('law_translations', 'law_translations.law_id', '=', 'law.id')
            ->where('law_translations.locale', '=', app()->getLocale());

        if($active) {
            $q->where('active', '=', 1);
        }

        return $q->orderBy('law_translations.name', 'asc')
            ->get();
    }
}
