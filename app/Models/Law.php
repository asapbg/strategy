<?php

namespace App\Models;

use App\Models\Consultations\PublicConsultation;
use App\Models\StrategicDocuments\Institution;
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

    public function institutions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Institution::class, 'law_institution', 'law_id', 'institution_id');
    }

    public function pc(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PublicConsultation::class, 'law_id', 'id');
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

    public static function select2AjaxOptions($filters)
    {
        $q = DB::table('law')
            ->select(['law.id', DB::raw('law_translations.name')])
            ->join('law_institution', 'law_institution.law_id', '=', 'law.id')
            ->join('law_translations', function ($j){
                $j->on('law.id', '=', 'law_translations.law_id')
                    ->where('law_translations.locale', '=', app()->getLocale());
            });

        if(isset($filters['search'])) {
            $q->where('law_translations.name', 'ilike', '%'.$filters['search'].'%');
        }

        $q->whereNull('law.deleted_at');
        $q->where('law.active', '=', 1);
        $q->groupBy('law.id', 'law_translations.name');

        return $q->orderBy('law_translations.name', 'asc')->get();
    }
}
