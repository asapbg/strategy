<?php

namespace App\Models\Consultations;

use App\Enums\DocTypesEnum;
use App\Enums\DynamicStructureTypesEnum;
use App\Models\File;
use App\Models\ModelActivityExtend;
use App\Traits\FilterSort;
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
        return $this->hasMany(OperationalProgramRow::class, 'operational_program_id', 'id');
    }

    public function rowFiles()
    {
        return $this->belongsToMany(File::class, 'operational_program_row_file', 'operational_program_id', 'file_id')
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
}
