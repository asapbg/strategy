<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Pris extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['about', 'legal_reason'];
    const MODULE_NAME = ('custom.pris_documents');
//
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'pris';

    //activity
    protected string $logName = "pris";

    protected $fillable = ['doc_num', 'doc_date', 'legal_act_type_id', 'institution_id', 'version',
        'protocol', 'public_consultation_id', 'newspaper_number', 'active', 'published_at'];

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->title;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'about' => [
                'type' => 'textarea',
                'rules' => ['required', 'string']
            ],
            'legal_reason' => [
                'type' => 'textarea',
                'rules' => ['required', 'string']
            ]
        );
    }

    public function actType(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(LegalActType::class, 'id', 'legal_act_type_id');
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'pris_tag', 'pris_id', 'tag_id');
    }

    public function changedDocs(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(self::class, 'pris_change_pris', 'changed_pris_id', 'pris_id');
    }

    public static function select2AjaxOptions($filters)
    {
        $q = DB::table('pris')
            ->select(['pris.id', DB::raw('pris.doc_num || \' (\' || legal_act_type_translations.name || \')\' as name')])
            ->join('legal_act_type', 'legal_act_type.id', '=', 'pris.legal_act_type_id')
            ->join('legal_act_type_translations', function ($j){
                $j->on('legal_act_type.id', '=', 'legal_act_type_translations.legal_act_type_id')
                    ->where('legal_act_type_translations.locale', '=', app()->getLocale());
            });
            if(isset($filters['search'])) {
                $q->where('pris.doc_num', 'ilike', '%'.$filters['doc_num'].'%');
            }
            $q->orderBy('legal_act_type_translations.name', 'asc')
            ->orderBy('pris.doc_num', 'asc');

        return $q->get();
    }
}
