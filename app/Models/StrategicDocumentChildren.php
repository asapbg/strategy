<?php

namespace App\Models;

use App\Models\Consultations\PublicConsultation;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class StrategicDocumentChildren extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['title', 'description'];
    const MODULE_NAME = ('custom.strategic_documents.documents');

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'strategic_document_children';

    //activity
    protected string $logName = "strategic_document_children";

    protected $fillable = ['strategic_document_id', 'strategic_document_level_id', 'accept_act_institution_type_id', 'strategic_document_type_id',
        'policy_area_id', 'pris_act_id', 'public_consultation_id', 'document_date_accepted', 'document_date_expiring', 'link_to_monitorstat'];

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->title;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'title' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:2000'],
                'required_all_lang' => false
            ],
            'description' => [
                'type' => 'summernote',
                'rules' => ['nullable', 'string'],
                'required_all_lang' => false
            ]
        );
    }


    protected function documentStatus(): Attribute
    {
        $currentDate = Carbon::now();
        return Attribute::make(
            get: fn (string|null $value) => $currentDate->between(Carbon::parse($this->document_date_accepted), Carbon::parse($this->document_date_expiring), true) ? trans('custom.strategic_document_active')
                : ($currentDate->greaterThan(Carbon::parse($this->document_date_expiring)) ? trans('custom.strategic_document_expired') : trans('custom.pending')),
        );
    }

    protected function documentDateAccepted(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => $value ? displayDate($value) : '',
            set: fn (string|null $value) => $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null
        );
    }

    protected function documentDateExpiring(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => $value ? displayDate($value) : '',
            set: fn (string|null $value) => $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null
        );
    }

    public function strategicDocument(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(StrategicDocument::class, 'id', 'strategic_document_id');
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(StrategicDocumentChildren::class, 'parent_id', 'id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StrategicDocumentChildren::class, 'id', 'parent_id');
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->where('code_object', File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN);
    }

    public function acceptActInstitution(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AuthorityAcceptingStrategic::class, 'id', 'accept_act_institution_type_id');
    }

    public function documentType(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(StrategicDocumentType::class, 'id', 'strategic_document_type_id');
    }

    public function publicConsultation(): hasOne
    {
        return $this->hasOne(PublicConsultation::class, 'id', 'public_consultation_id');
    }


    public function policyArea(): HasOne
    {
        return $this->hasOne(FieldOfAction::class, 'id', 'policy_area_id');
    }

    public function pris(): HasOne
    {
        return $this->hasOne(Pris::class, 'id', 'pris_act_id');
    }

    /**
     * We use this to draw documents tree
     * If $id is 0 then we get full tree
     * @return array
     */
    public static function getTree($id = 0, $sd = 0, $onlyVisible = false)
    {
        $tree = [];
        $documents = DB::select(
            'select
                        strategic_document_children.id,
                        strategic_document_children.strategic_document_id as sd_id,
                        strategic_document_children.strategic_document_level_id,
                        strategic_document_children.accept_act_institution_type_id,
                        strategic_document_children.strategic_document_type_id,
                        max(strategic_document_type_translations.name) as strategic_document_type_name,
                        strategic_document_children.document_date_accepted,
                        strategic_document_children.document_date_expiring,
                        strategic_document_children.link_to_monitorstat,
                        strategic_document_children.public_consultation_id,
                        strategic_document_children.pris_act_id,
                        max(public_consultation.reg_num) as consultation_reg_num,
                        concat( max(pris.doc_num) || \'/\' || extract(year from max(pris.doc_date)))::text as pris_reg_num,
                        max(pris.legal_act_type_id) as pris_legal_act_type_id,
                        max(authority_accepting_strategic_translations.name) as accept_act_institution_name,
                        json_agg(json_build_object(\'locale\', strategic_document_children_translations.locale, \'title\', strategic_document_children_translations.title, \'description\', strategic_document_children_translations.description)) as translations,
                        (select json_agg(json_build_object(\'id\', files.id, \'path\', files.path, \'type\', files.content_type, \'locale\', files.locale, \'description_bg\', files.description_bg, \'description_en\', files.description_en, \'created_at\', files.created_at, \'is_visible\', files.is_visible)) from files where files.id_object = strategic_document_children.id and files.code_object = '. File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN .' and files.deleted_at is null '. ($onlyVisible ? ' and files.is_visible = 1' : '') .') as files
                    from strategic_document_children
                    left join strategic_document_children_translations on strategic_document_children_translations.strategic_document_children_id = strategic_document_children.id
                    left join authority_accepting_strategic on strategic_document_children.accept_act_institution_type_id = authority_accepting_strategic.id
                    left join authority_accepting_strategic_translations on authority_accepting_strategic_translations.authority_accepting_strategic_id = authority_accepting_strategic.id and authority_accepting_strategic_translations.locale = \''.app()->getLocale().'\'
                    left join strategic_document_type on strategic_document_children.strategic_document_type_id = strategic_document_type.id
                    left join strategic_document_type_translations on strategic_document_type_translations.strategic_document_type_id = strategic_document_type.id and strategic_document_type_translations.locale = \''.app()->getLocale().'\'
                    left join public_consultation on public_consultation.id = strategic_document_children.public_consultation_id
                    left join pris on pris.id = strategic_document_children.pris_act_id

                    --left join files on files.id_object = strategic_document_children.id and files.code_object = '. File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN .' and files.deleted_at is null '. ($onlyVisible ? ' and files.is_visible = 1' : '') .'
                    where
                        strategic_document_children.deleted_at is null
                        '. ($id ? ' and strategic_document_children.id = '.(int)$id : ' and strategic_document_children.parent_id is null ') .'
                        '. ($sd ? ' and strategic_document_children.strategic_document_id = '.(int)$sd : '') .'
                    group by strategic_document_children.id
                ');

        if(sizeof($documents)) {
            foreach ($documents as $d){
                $d->children = self::documentChildren($d->id);
                $tree[] = $d;
            }
        }

        return $tree;
    }

    private static function documentChildren(int $parent, $level = 1, $onlyVisible = false): array
    {
        $children = [];
        $documents = DB::select(
            'select
                        strategic_document_children.id,
                        strategic_document_children.strategic_document_id as sd_id,
                        strategic_document_children.strategic_document_level_id,
                        strategic_document_children.accept_act_institution_type_id,
                        strategic_document_children.strategic_document_type_id,
                        max(strategic_document_type_translations.name) as strategic_document_type_name,
                        strategic_document_children.document_date_accepted,
                        strategic_document_children.document_date_expiring,
                        strategic_document_children.link_to_monitorstat,
                        strategic_document_children.public_consultation_id,
                        strategic_document_children.pris_act_id,
                        max(public_consultation.reg_num) as consultation_reg_num,
                        concat( max(pris.doc_num) || \'/\' || extract(year from max(pris.doc_date))) as pris_reg_num,
                        max(pris.legal_act_type_id) as pris_legal_act_type_id,
                        max(authority_accepting_strategic_translations.name) as accept_act_institution_name,
                        json_agg(json_build_object(\'locale\', strategic_document_children_translations.locale, \'title\', strategic_document_children_translations.title, \'description\', strategic_document_children_translations.description)) as translations,
                        (select json_agg(json_build_object(\'id\', files.id, \'path\', files.path, \'type\', files.content_type, \'locale\', files.locale, \'description_bg\', files.description_bg, \'description_en\', files.description_en, \'created_at\', files.created_at, \'is_visible\', files.is_visible)) from files where files.id_object = strategic_document_children.id and files.code_object = '. File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN .' and files.deleted_at is null '. ($onlyVisible ? ' and files.is_visible = 1' : '') .') as files
                    from strategic_document_children
                    join strategic_document_children_translations on strategic_document_children_translations.strategic_document_children_id = strategic_document_children.id
                    left join authority_accepting_strategic on strategic_document_children.accept_act_institution_type_id = authority_accepting_strategic.id
                    left join authority_accepting_strategic_translations on authority_accepting_strategic_translations.authority_accepting_strategic_id = authority_accepting_strategic.id and authority_accepting_strategic_translations.locale = \''.app()->getLocale().'\'
                    left join strategic_document_type on strategic_document_children.strategic_document_type_id = strategic_document_type.id
                    left join strategic_document_type_translations on strategic_document_type_translations.strategic_document_type_id = strategic_document_type.id and strategic_document_type_translations.locale = \''.app()->getLocale().'\'
                    left join public_consultation on public_consultation.id = strategic_document_children.public_consultation_id
                    left join pris on pris.id = strategic_document_children.pris_act_id
                    -- left join files on files.id_object = strategic_document_children.id and files.code_object = '. File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN .' and files.deleted_at is null '. ($onlyVisible ? ' and files.is_visible = 1' : '') .'
                    where
                        strategic_document_children.deleted_at is null
                        and strategic_document_children.parent_id = '.$parent.'
                    group by strategic_document_children.id
                ');

        if( sizeof($documents) ) {
            foreach ($documents as $c) {
                $c->level = $level;
                $c->children = self::documentChildren($c->id, ($level + 1), $onlyVisible);
                $children[] = $c;
            }
        }
        return $children;
    }
}
