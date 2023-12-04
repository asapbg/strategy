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
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class OperationalProgram extends ModelActivityExtend
{
    use FilterSort, SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.operational_program');
    public $timestamps = true;
    protected $table = 'operational_program';

    //activity
    protected string $logName = "operational_program";

    protected $guarded = [];

    /**
     * Get the model name
     */
    public function getModelName() {
        return __('custom.dynamic_structures.type.'.DynamicStructureTypesEnum::keyByValue($this->type));
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
            get: fn () => __('custom.operational_program_title', ['monthFrom' => __('site.'.(int)date('m', strtotime($this->from_date))), 'monthTo' => __('site.'.(int)date('m', strtotime($this->to_date))), 'year' => date('Y', strtotime($this->to_date))])
        );
    }

    public function records(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OperationalProgramRow::class, 'operational_program_id', 'id');
    }

    public function rowFiles()
    {
        return $this->belongsToMany(File::class, 'operational_program_row_file', 'operational_program_id', 'file_id')
            ->where('code_object', '=', File::CODE_OBJ_OPERATIONAL_PROGRAM)
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
                        json_agg(json_build_object(\'id\', operational_program_row.id, \'value\', operational_program_row.value, \'type\', dynamic_structure_column.type, \'dsc_id\', dynamic_structure_column.id, \'ord\', dynamic_structure_column.ord, \'label\', dynamic_structure_column_translations.label)) as columns
                    from operational_program_row
                    join dynamic_structure_column on dynamic_structure_column.id = operational_program_row.dynamic_structures_column_id
                    join dynamic_structure_column_translations on dynamic_structure_column_translations.dynamic_structure_column_id = dynamic_structure_column.id and dynamic_structure_column_translations.locale = \''.app()->getLocale().'\'
                    where
                        operational_program_row.operational_program_id = '.(int)$this->id.'
                        and operational_program_row.deleted_at is null
                    group by operational_program_row.month, operational_program_row.row_num
                    order by operational_program_row.month, operational_program_row.row_num asc
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
                    ->where('operational_program_row.dynamic_structures_column_id', '=', OperationalProgramController::DYNAMIC_STRUCTURE_COLUMN_TITLE_ID);
            })
            ->leftJoin('public_consultation', function ($j){
                $j->on('public_consultation.operational_program_id', '=', 'operational_program.id')
                    ->whereColumn('public_consultation.operational_program_row_id', '=', 'operational_program_row.id');
            });
        if(isset($filters['programId']) && (int)$filters['programId']) {
            $q->where('operational_program.id', '=', (int)$filters['programId']);
        }
        if(isset($filters['search'])) {
            $q->where('operational_program_row.value', 'ilike', '%'.$filters['search'].'%');
        }
        $q->whereNull('public_consultation.id');

        return $q->get();
    }

    public static function select2AjaxOptionsFilterByInstitution($filters){
        $q = DB::table('operational_program')
            ->select(['operational_program_row.id',
                DB::raw('max(operational_program_row.value) || \' [Програма \' || max(to_char(operational_program.from_date, \'MM.YYYY\')) || \' - \' || max(to_char(operational_program.to_date, \'MM.YYYY\')) || \']\' as name')])
            ->join('operational_program_row', function ($j){
                $j->on('operational_program_row.operational_program_id', '=', 'operational_program.id')
                    ->where('operational_program_row.dynamic_structures_column_id', '=', OperationalProgramController::DYNAMIC_STRUCTURE_COLUMN_TITLE_ID);
            })
            ->leftJoin('public_consultation', function ($j){
                $j->on('public_consultation.operational_program_id', '=', 'operational_program.id')
                    ->whereColumn('public_consultation.operational_program_row_id', '=', 'operational_program_row.id');
            });

        if(isset($filters['institution'])) {
            $q->join('operational_program_row as institution_col', function ($j) use($filters){
                $j->on('institution_col.operational_program_id', '=', 'operational_program_row.operational_program_id')
                    ->on('institution_col.row_num', '=', 'operational_program_row.row_num')
                    ->where('institution_col.dynamic_structures_column_id', '=', OperationalProgramController::DYNAMIC_STRUCTURE_COLUMN_INSTITUTION_ID)
                    ->where('institution_col.value', '=', (int)$filters['institution']);
            });
        }
        if(isset($filters['programId']) && (int)$filters['programId']) {
            $q->where('operational_program.id', '=', (int)$filters['programId']);
        }
        if(isset($filters['search'])) {
            $q->where('operational_program_row.value', 'ilike', '%'.$filters['search'].'%');
        }
        $q->whereNull('legislative_program.deleted_at');

        $q->groupBy('operational_program_row.id');

        return $q->get();
    }
}
