<?php

namespace App\Models\Consultations;

use App\Enums\DocTypesEnum;
use App\Enums\DynamicStructureTypesEnum;
use App\Http\Controllers\Admin\Consultations\OperationalProgramController;
use App\Models\File;
use App\Models\ModelActivityExtend;
use App\Traits\FilterSort;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

class OperationalProgram extends ModelActivityExtend implements Feedable
{
    use FilterSort;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.operational_program');
    public $timestamps = true;
    protected $table = 'operational_program';
    const DEFAULT_IMG = 'images'.DIRECTORY_SEPARATOR.'ms-2023.jpg';

    //activity
    protected string $logName = "operational_program";

    protected $guarded = [];

    /**
     * @return FeedItem
     */
    public function toFeedItem(): FeedItem
    {
        $extraInfo = '';
        return FeedItem::create([
            'id' => $this->id,
            'title' => $this->name,
            'summary' => $extraInfo,
            'updated' => $this->updated_at,
            'created' => $this->created_at,
            'enclosure' => asset(self::DEFAULT_IMG),
            'link' => route('op.view', ['id' => $this->id]),
            'authorName' => '',
            'authorEmail' => ''
        ]);
    }

    /**
     * We use this method for rss feed
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getFeedItems(): \Illuminate\Database\Eloquent\Collection
    {
        return static::Published()
            ->orderByRaw("created_at desc")
            ->limit(config('feed.items_per_page'), 20)
            ->get();
    }

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->name;
    }

    public function scopeNotLockedOrByCd($query, $excludeCdId = 0)
    {
        $query->where(function ($q) use ($excludeCdId) {
            $q->where('locked', '=', 0);
            if( $excludeCdId ) {
                $q->orWhere('public_consultation_id', '=', $excludeCdId);
            }
        });
    }

    public function scopeExpired($query)
    {
        $now = databaseDate(Carbon::now());
        $query->where('to_date', '<', $now);
    }

    public function scopeActual($query)
    {
        $now = databaseDate(Carbon::now());
        $query->where(function ($q) use ($now) {
            $q->where('from_date', '<=', $now)
                ->where('to_date', '>=', $now);
        });
    }

    public function scopePublished($query)
    {
        $query->where('public', 1);
    }

    /**
     * Program period
     */
    protected function period(): Attribute
    {
        return Attribute::make(
            get: fn () => date('m.Y', strtotime($this->from_date)).' - '.date('m.Y', strtotime($this->to_date))
        );
    }

    protected function isActual(): Attribute
    {
        $now = Carbon::now()->format('Y-m-d');
        return Attribute::make(
            get: fn () => (Carbon::parse($this->from_date)->format('Y-m-d') <= $now && Carbon::parse($this->to_date)->format('Y-m-d') >= $now)
        );
    }

    protected function recordPeriod(): Attribute
    {
        return Attribute::make(
            get: fn () => '['.trans_choice('custom.programs', 1).' '.date('m.Y', strtotime($this->from_date)).' - '.date('m.Y', strtotime($this->to_date)).']'
        );
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => __('custom.operational_program_title', ['monthFrom' => mb_strtolower(__('site.'.(int)date('m', strtotime($this->from_date)))), 'monthTo' => mb_strtolower(__('site.'.(int)date('m', strtotime($this->to_date)))), 'year' => date('Y', strtotime($this->to_date))])
        );
    }

    public function records(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OperationalProgramRow::class, 'operational_program_id', 'id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->where('code_object', File::CODE_OBJ_OPERATIONAL_PROGRAM_GENERAL);
    }

    public function filesLocale(): HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->where('code_object', File::CODE_OBJ_OPERATIONAL_PROGRAM_GENERAL)
            ->where('locale', '=', app()->getLocale());
    }

    public function rowFiles()
    {
        return $this->belongsToMany(File::class, 'operational_program_row_file', 'operational_program_id', 'file_id')
            ->where('code_object', '=', File::CODE_OBJ_OPERATIONAL_PROGRAM)
            ->withPivot('row_num')
            ->withPivot('row_month');
    }

    public function rowFilesLocale()
    {
        return $this->belongsToMany(File::class, 'operational_program_row_file', 'operational_program_id', 'file_id')
            ->where('code_object', '=', File::CODE_OBJ_OPERATIONAL_PROGRAM)
            ->where('locale', '=', app()->getLocale())
            ->withPivot('row_num')
            ->withPivot('row_month');
    }

    public function assessments()
    {
        return $this->belongsToMany(File::class, 'operational_program_row_file', 'operational_program_id', 'file_id')
            ->where('code_object', '=', File::CODE_OBJ_OPERATIONAL_PROGRAM)
            ->where('doc_type', '=', DocTypesEnum::PC_IMPACT_EVALUATION)
            ->withPivot('row_num')
            ->withPivot('row_month');
    }

    public function opinions()
    {
        return $this->belongsToMany(File::class, 'operational_program_row_file', 'operational_program_id', 'file_id')
            ->where('code_object', '=', File::CODE_OBJ_OPERATIONAL_PROGRAM)
            ->where('doc_type', '=', DocTypesEnum::PC_IMPACT_EVALUATION_OPINION)
            ->withPivot('row_num')
            ->withPivot('row_month');
    }

    public function getTableData()
    {
        return DB::select(
            'select
                        operational_program_row.month,
                        operational_program_row.row_num,
                        max(case when operational_program_row.dynamic_structures_column_id = '.config('lp_op_programs.op_ds_col_number_id').' then operational_program_row.value::int else 0 end) as record_num,
                        json_agg(json_build_object(\'id\', operational_program_row.id, \'value\', operational_program_row.value, \'type\', dynamic_structure_column.type, \'dsc_id\', dynamic_structure_column.id, \'ord\', dynamic_structure_column.ord, \'label\', dynamic_structure_column_translations.label, \'institution_ids\', (select json_agg(operational_program_row_institution.institution_id) as institution_ids from operational_program_row_institution where operational_program_row_institution.operational_program_row_id = operational_program_row.id))) as columns,
                        json_agg(institution_translations.name) FILTER (where institution_translations.name is not null) as name_institutions
                    from operational_program_row
                    join dynamic_structure_column on dynamic_structure_column.id = operational_program_row.dynamic_structures_column_id
                    join dynamic_structure_column_translations on dynamic_structure_column_translations.dynamic_structure_column_id = dynamic_structure_column.id and dynamic_structure_column_translations.locale = \''.app()->getLocale().'\'
                    left join operational_program_row_institution on operational_program_row_institution.operational_program_row_id = operational_program_row.id
                    left join institution on institution.id = operational_program_row_institution.institution_id
                    left join institution_translations on institution_translations.institution_id = institution.id and institution_translations.locale = \''.app()->getLocale().'\'
                    where
                        operational_program_row.operational_program_id = '.(int)$this->id.'
                        and operational_program_row.deleted_at is null
                    group by operational_program_row.month, operational_program_row.row_num
                    order by operational_program_row.month, record_num asc
                ');
    }

    public static function select2AjaxOptions($filters)
    {
        $select = ['operational_program_row.id', 'operational_program_row.value as name'];

        if (isset($filters['op_record'])) {
            $select = ['operational_program_row.operational_program_id as id', 'operational_program_row.value as name'];
        }

        $q = DB::table('operational_program')
            ->select($select)
            ->join('operational_program_row', function ($j){
                $j->on('operational_program_row.operational_program_id', '=', 'operational_program.id')
                    ->where('operational_program_row.dynamic_structures_column_id', '=', config('lp_op_programs.op_ds_col_title_id'));
            })
            ->leftJoin('public_consultation', function ($j){
                $j->on('public_consultation.operational_program_id', '=', 'operational_program.id')
                    ->whereColumn('public_consultation.operational_program_row_id', '=', 'operational_program_row.id');
            });
        if(isset($filters['programId']) && $filters['programId'] != '') {
            $q->where('operational_program.id', '=', (int)$filters['programId']);
        }
        if(isset($filters['search'])) {
            $q->where('operational_program_row.value', 'ilike', '%'.$filters['search'].'%');
        }
        $q->whereNull('public_consultation.id');

        return $q->get();
    }

    /**
     * DO NOT CHANGE WITHOUT DISCUSSION !!!
     * @param $filters
     * @return Collection
     */
    public static function select2AjaxOptionsFilterByInstitution($filters){
        $q = DB::table('operational_program')
            ->select(['operational_program_row.id',
                DB::raw('max(operational_program_row.value) || \' [Програма \' || max(to_char(operational_program.from_date, \'MM.YYYY\')) || \' - \' || max(to_char(operational_program.to_date, \'MM.YYYY\')) || \']\' as name')])
            ->join('operational_program_row', function ($j){
                $j->on('operational_program_row.operational_program_id', '=', 'operational_program.id')
                    ->where('operational_program_row.dynamic_structures_column_id', '=', config('lp_op_programs.op_ds_col_title_id'));
            })
            ->leftJoin('public_consultation', function ($j){
                $j->on('public_consultation.operational_program_id', '=', 'operational_program.id')
                    ->whereColumn('public_consultation.operational_program_row_id', '=', 'operational_program_row.id');
            });

        if(isset($filters['institution'])) {
            $q->join('operational_program_row as institution_col', function ($j) use($filters){
                $j->on('institution_col.operational_program_id', '=', 'operational_program_row.operational_program_id')
                    ->on('institution_col.row_num', '=', 'operational_program_row.row_num')
                    ->where('institution_col.dynamic_structures_column_id', '=', config('lp_op_programs.op_ds_col_institution_id'));
            })->join('operational_program_row_institution', function ($j) use($filters){
                $j->on('operational_program_row_institution.operational_program_row_id', '=', 'institution_col.id')
                    ->where('operational_program_row_institution.institution_id', '=', (int)$filters['institution']);
            });
        }
        if(isset($filters['programId']) && $filters['programId'] != '') {
            $q->where('operational_program.id', '=', (int)$filters['programId']);
        }
        if(isset($filters['search'])) {
            $q->where('operational_program_row.value', 'ilike', '%'.$filters['search'].'%');
        }
        $q->whereNull('operational_program.deleted_at');
        $q->where('operational_program_row.dynamic_structures_column_id', '=', config('lp_op_programs.op_ds_col_title_id'));

        $q->groupBy('operational_program_row.id');

        return $q->get();
    }

    public static function list(array $filter){
        return self::Published()
            ->FilterBy($filter)
            ->orderBy('from_date', 'desc')
            ->get();
    }
}
