<?php

namespace App\Models\StrategicDocuments;

use App\Models\EkatteSettlement;
use App\Models\InstitutionLevel;
use App\Models\InstitutionLink;
use App\Models\ModelActivityExtend;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Institution extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name', 'address', 'add_info'];
    const MODULE_NAME = ('custom.nomenclatures.institution');
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'institution';

    //activity
    protected string $logName = "institution";

    protected $guarded = [];

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->name;
    }

    public function level(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(InstitutionLevel::class, 'id', 'institution_level_id');
    }

    public function links()
    {
        return $this->hasMany(InstitutionLink::class, 'institution_id', 'id');
    }

    public function settlement()
    {
        return $this->hasOne(EkatteSettlement::class, 'id', 'town');
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
        return DB::table('institution')
            ->select(['institution.id', 'institution_translations.name'])
            ->join('institution_translations', 'institution_translations.institution_id', '=', 'institution.id')
            ->where('institution_translations.locale', '=', app()->getLocale())
            ->orderBy('institution_translations.name', 'asc')
            ->get();
    }

    public static function simpleOptionsList(): \Illuminate\Support\Collection
    {
        return DB::table('institution')
            ->select(['institution.id', 'institution_translations.name'])
            ->join('institution_translations', 'institution_translations.institution_id', '=', 'institution.id')
            ->where('institution.active', '=', 1)
            ->where('institution_translations.locale', '=', app()->getLocale())
            ->orderBy('institution_translations.name', 'asc')
            ->get();
    }

    /**
     * We use this to draw subjects tree template in modals and pages
     * @return array
     */
    public static function getTree($filter = [])
    {
        $tree = [];
        $subjects = DB::table('institution')
            ->select(['institution.id'
                , 'institution_translations.name'
                , 'institution.institution_level_id as section_parent'
                , 'institution.parent_id as institution_parent'
//                , DB::raw('case when institution.parent_id is null then institution.institution_level_id else institution.parent_id end as parent')
                , DB::raw('1 as selectable')])
            ->join('institution_translations', 'institution_translations.institution_id', '=', 'institution.id')
            ->where('institution.active', '=', 1)
            ->whereNull('institution.deleted_at')
            ->where('institution_translations.locale', '=', app()->getLocale());

        $allSubjectsAndSections = DB::table("institution_level")
            ->select(['institution_level.id'
                , 'institution_level_translations.name'
                , 'institution_level.parent_id as section_parent'
                , DB::raw('null as institution_parent')
                , DB::raw('0 as selectable')])
            ->join('institution_level_translations', 'institution_level_translations.institution_level_id', '=', 'institution_level.id')
            ->where('institution_level.active', '=', 1)
            ->where('institution_level_translations.locale', '=', app()->getLocale())
            ->union($subjects)->orderBy('name','asc')
            ->get();
        if( $allSubjectsAndSections->count() ) {
            foreach ($allSubjectsAndSections as $subject) {
                if( !$subject->selectable && !$subject->section_parent ) {
                    $tree[] = array(
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'selectable' => $subject->selectable,
                        'parent' => null,
                        'children' => self::subjectChildren($subject->id, !$subject->selectable, $allSubjectsAndSections)
                    );
                }
            }
        }
        return $tree;
    }

    private static function subjectChildren(int $parent, int $parentIsSection, $subjects): array
    {
        $children = [];
        if( $subjects->count() ) {
            foreach ($subjects as $subject) {
                $isSubjectChild = !$parentIsSection && (int)$subject->institution_parent == $parent;
                $isSectionChild = $parentIsSection &&
                    (
                        (!$subject->selectable && (int)$subject->section_parent == $parent)
                        || ($subject->selectable && (int)$subject->section_parent == $parent && is_null($subject->institution_parent))
                    );
                if($isSectionChild || $isSubjectChild) {
                    $children[] = array(
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'selectable' => $subject->selectable,
                        'parent' => $subject->institution_parent ?? $subject->section_parent,
                        'children' => self::subjectChildren($subject->id, !$subject->selectable, $subjects)
                    );
                }
            }
        }
        return $children;
    }
}
