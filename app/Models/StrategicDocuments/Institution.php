<?php

namespace App\Models\StrategicDocuments;

use App\Models\AdvisoryBoard;
use App\Models\Consultations\PublicConsultation;
use App\Models\EkatteArea;
use App\Models\EkatteMunicipality;
use App\Models\EkatteSettlement;
use App\Models\FieldOfAction;
use App\Models\InstitutionHistoryName;
use App\Models\InstitutionLevel;
use App\Models\InstitutionLink;
use App\Models\Law;
use App\Models\LegislativeInitiative;
use App\Models\ModelActivityExtend;
use App\Models\Pris;
use App\Models\User;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Institution extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, Notifiable;

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
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification): array|string
    {
        return config('app.env') != 'production' ? config('mail.local_to_mail') : $this->email;

    }

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

    /**
     * Get institution name changes through time
     *
     * @return HasMany
     */
    public function historyNames()
    {
        return $this->hasMany(InstitutionHistoryName::class)->orderBy('valid_from');
    }

    /**
     * Get the name of the institution for a given date
     *
     * @param $date
     * @return string
     */
    public function getHistorycalName($date)
    {
        $date = databaseDate($date);
        $name = $this->name;
        $hName = $this->historyNames->filter(function ($item) use ($date) {
            return $item->valid_from <= $date && (is_null($item->valid_till) || $item->valid_till > $date);
        });
        if ($hName->count() > 0) {
            $name = $hName->first()->name;
        }
        return $name;
    }

    public function settlement()
    {
        return $this->hasOne(EkatteSettlement::class, 'id', 'town');
    }

    public function area()
    {
        return $this->hasOne(EkatteArea::class, 'id', 'region');
    }

    public function municipal()
    {
        return $this->hasOne(EkatteMunicipality::class, 'id', 'municipality');
    }

    public function laws()
    {
        return $this->belongsToMany(Law::class, 'law_institution', 'institution_id', 'law_id');
    }

    public function fieldsOfAction()
    {
        return $this->belongsToMany(FieldOfAction::class, 'institution_field_of_action', 'institution_id', 'field_of_action_id');
    }

    public function fieldsOfActionOrdered()
    {
        return $this->belongsToMany(FieldOfAction::class, 'institution_field_of_action', 'institution_id', 'field_of_action_id')->orderBy('parentid')->orderByTranslation('name');
    }

    public function publicConsultation(): HasMany
    {
        return $this->hasMany(PublicConsultation::class, 'importer_institution_id', 'id')->ActivePublic();
    }

    public function pris(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Pris::class, 'pris_institution', 'institution_id', 'pris_id')->Published();
    }

    public function legislativeInitiatives()
    {
        return LegislativeInitiative::select(['legislative_initiative.*'])
            ->with(['user', 'law', 'law.translation', 'likes', 'dislikes'])
            ->join('law', 'law.id', '=', 'legislative_initiative.law_id')
            ->join('law_institution', function ($query) {
                $query->on('law_institution.law_id', '=', 'law.id')->where('law_institution.institution_id', '=', $this->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function advisoryBoards()
    {
        return AdvisoryBoard::select(['advisory_boards.*'])
            ->with(['policyArea', 'policyArea.translations', 'translations', 'moderators',
                'authority', 'authority.translations', 'advisoryChairmanType', 'advisoryChairmanType.translations',
                'advisoryActType', 'advisoryActType.translations'])
            ->leftJoin('advisory_board_translations', function ($j){
                $j->on('advisory_board_translations.advisory_board_id', '=', 'advisory_boards.id')
                    ->where('advisory_board_translations.locale', '=', app()->getLocale());
            })->whereHas('chairmen', function ($q){
                $q->where('institution_id', '=', $this->id);
            })
            ->where('public', true)
            ->orderBy('advisory_boards.active', 'desc')
            ->orderBy('advisory_board_translations.name')
            ->get();
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

    public function moderators()
    {
        return $this->hasMany(User::class, 'institution_id', 'id');
    }

    public static function optionsList($withDefault = true)
    {
        return DB::table('institution')
            ->select(['institution.id', 'institution_translations.name'])
            ->join('institution_translations', 'institution_translations.institution_id', '=', 'institution.id')
            ->where('institution_translations.locale', '=', app()->getLocale())
            ->where('institution.id', '<>', config('app.default_institution_id'))
            ->orderBy('institution_translations.name', 'asc')
            ->get();
    }

    public static function simpleOptionsList(): \Illuminate\Support\Collection
    {
        return DB::table('institution')
            ->select(['institution.id', 'institution_translations.name', DB::raw('max(institution_level.nomenclature_level) as level')])
            ->join('institution_translations', 'institution_translations.institution_id', '=', 'institution.id')
            ->join('institution_level', 'institution_level.id', '=', 'institution.institution_level_id')
            ->where('institution.active', '=', 1)
            ->where('institution_translations.locale', '=', app()->getLocale())
            ->where('institution.id', '<>', config('app.default_institution_id'))
            ->orderBy('institution_translations.name', 'asc')
            ->groupBy('institution.id', 'institution_translations.name')
            ->get();
    }

    public static function optionsListWithAttr(): \Illuminate\Support\Collection
    {
        return DB::table('institution')
            ->select(['institution.id as value', 'institution_translations.name',
                DB::raw('json_agg(institution_field_of_action.field_of_action_id) as foa'),
                DB::raw('case when max(law_institution.law_id) is null then \'[]\' else json_agg(distinct(law_institution.law_id)) end as laws'),
                DB::raw('max(institution_level.nomenclature_level) as level')])
            ->join('institution_translations', 'institution_translations.institution_id', '=', 'institution.id')
            ->join('institution_field_of_action', 'institution_field_of_action.institution_id', '=', 'institution.id')
            ->join('institution_level', 'institution_level.id', '=', 'institution.institution_level_id')
            ->leftJoin('law_institution', 'law_institution.institution_id', '=', 'institution.id')
            ->where('institution.active', '=', 1)
            ->where('institution_translations.locale', '=', app()->getLocale())
            ->where('institution.id', '<>', config('app.default_institution_id'))
            ->orderBy('institution_translations.name', 'asc')
            ->groupBy('institution.id', 'institution_translations.name')
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
            ->where('institution.id', '<>', config('app.default_institution_id'))
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
            ->where('institution_level.id', '<>', config('app.default_institution_level_id'))
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
