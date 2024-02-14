<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
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

    protected $fillable = [];

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
                        json_agg(json_build_object(\'locale\', strategic_document_children_translations.locale, \'title\', strategic_document_children_translations.title, \'description\', strategic_document_children_translations.description)) as translations,
                        (select json_agg(json_build_object(\'id\', files.id, \'path\', files.path, \'type\', files.content_type, \'locale\', files.locale, \'description_bg\', files.description_bg, \'description_en\', files.description_en, \'created_at\', files.created_at, \'is_visible\', files.is_visible)) from files where files.id_object = strategic_document_children.id and files.code_object = '. File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN .' and files.deleted_at is null '. ($onlyVisible ? ' and files.is_visible = 1' : '') .') as files
                    from strategic_document_children
                    left join strategic_document_children_translations on strategic_document_children_translations.strategic_document_children_id = strategic_document_children.id
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
                        json_agg(json_build_object(\'locale\', strategic_document_children_translations.locale, \'title\', strategic_document_children_translations.title, \'description\', strategic_document_children_translations.description)) as translations,
                        (select json_agg(json_build_object(\'id\', files.id, \'path\', files.path, \'type\', files.content_type, \'locale\', files.locale, \'description_bg\', files.description_bg, \'description_en\', files.description_en, \'created_at\', files.created_at, \'is_visible\', files.is_visible)) from files where files.id_object = strategic_document_children.id and files.code_object = '. File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN .' and files.deleted_at is null '. ($onlyVisible ? ' and files.is_visible = 1' : '') .') as files
                    from strategic_document_children
                    join strategic_document_children_translations on strategic_document_children_translations.strategic_document_children_id = strategic_document_children.id
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
