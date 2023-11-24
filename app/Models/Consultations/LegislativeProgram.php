<?php

namespace App\Models\Consultations;

use App\Enums\DocTypesEnum;
use App\Enums\DynamicStructureTypesEnum;
use App\Http\Controllers\Admin\Consultations\LegislativeProgramController;
use App\Models\File;
use App\Models\ModelActivityExtend;
use App\Traits\FilterSort;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class LegislativeProgram extends ModelActivityExtend
{
    use FilterSort, SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.nomenclatures.legislative_program');
    public $timestamps = true;
    protected $table = 'legislative_program';

    //activity
    protected string $logName = "legislative_program";

    protected $guarded = [];

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

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => date('m.Y', strtotime($this->from_date)).' - '.date('m.Y', strtotime($this->to_date))
        );
    }

    public function records(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LegislativeProgramRow::class, 'legislative_program_id', 'id');
    }

    public function rowFiles()
    {
        return $this->belongsToMany(File::class, 'legislative_program_row_file', 'legislative_program_id', 'file_id')
            ->where('code_object', '=', File::CODE_OBJ_LEGISLATIVE_PROGRAM)
            ->withPivot('row_num')
            ->withPivot('row_month');
    }

    public function assessments()
    {
        return $this->belongsToMany(File::class, 'legislative_program_row_file', 'legislative_program_id', 'file_id')
            ->where('code_object', '=', File::CODE_OBJ_LEGISLATIVE_PROGRAM)
            ->where('doc_type', '=', DocTypesEnum::PC_IMPACT_EVALUATION)
            ->withPivot('row_num')
            ->withPivot('row_month');
    }

    public function opinions()
    {
        return $this->belongsToMany(File::class, 'legislative_program_row_file', 'legislative_program_id', 'file_id')
            ->where('code_object', '=', File::CODE_OBJ_LEGISLATIVE_PROGRAM)
            ->where('doc_type', '=', DocTypesEnum::PC_IMPACT_EVALUATION_OPINION)
            ->withPivot('row_num')
            ->withPivot('row_month');
    }

    public function getTableData()
    {
        return DB::select(
            'select
                        legislative_program_row.month,
                        legislative_program_row.row_num,
                        json_agg(json_build_object(\'id\', legislative_program_row.id, \'value\', legislative_program_row.value, \'type\', dynamic_structure_column.type, \'dsc_id\', dynamic_structure_column.id, \'ord\', dynamic_structure_column.ord, \'label\', dynamic_structure_column_translations.label)) as columns
                    from legislative_program_row
                    join dynamic_structure_column on dynamic_structure_column.id = legislative_program_row.dynamic_structures_column_id
                    join dynamic_structure_column_translations on dynamic_structure_column_translations.dynamic_structure_column_id = dynamic_structure_column.id and dynamic_structure_column_translations.locale = \''.app()->getLocale().'\'
                    where
                        legislative_program_row.legislative_program_id = '.(int)$this->id.'
                        and legislative_program_row.deleted_at is null
                    group by legislative_program_row.month, legislative_program_row.row_num
                    order by legislative_program_row.month, legislative_program_row.row_num asc
                ');
    }

    public static function select2AjaxOptions($filters)
    {
        $q = DB::table('legislative_program')
            ->select(['legislative_program_row.id', 'legislative_program_row.value as name'])
            ->join('legislative_program_row', function ($j){
                $j->on('legislative_program_row.legislative_program_id', '=', 'legislative_program.id')
                    ->where('legislative_program_row.dynamic_structures_column_id', '=', LegislativeProgramController::DYNAMIC_STRUCTURE_COLUMN_TITLE_ID);
            })
            ->leftJoin('public_consultation', function ($j){
                $j->on('public_consultation.legislative_program_id', '=', 'legislative_program.id')
                    ->whereColumn('public_consultation.legislative_program_row_id', '=', 'legislative_program_row.id');
            });
        if(isset($filters['programId']) && (int)$filters['programId']) {
            $q->where('legislative_program.id', '=', (int)$filters['programId']);
        }
        if(isset($filters['search'])) {
            $q->where('legislative_program_row.value', 'ilike', '%'.$filters['search'].'%');
        }
        $q->whereNull('public_consultation.id');

        return $q->get();
    }
}
