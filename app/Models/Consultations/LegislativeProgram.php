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

class LegislativeProgram extends ModelActivityExtend
{
    use FilterSort, SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = 'custom.nomenclatures.legislative_program';
    public $timestamps = true;
    protected $table = 'legislative_program';

    //activity
    protected string $logName = "legislative_program";

    protected $guarded = [];

    public function getModelName() {
        return __('custom.dynamic_structures.type.'.DynamicStructureTypesEnum::keyByValue($this->type));
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

    public function records(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LegislativeProgramRow::class, 'legislative_program_id', 'id');
    }

    public function assessment()
    {
        return $this->hasOne(File::class, 'id_object', 'id')
            ->where('code_object', '=', File::CODE_OBJ_LEGISLATIVE_PROGRAM)
            ->where('doc_type', '=', DocTypesEnum::PC_IMPACT_EVALUATION);
    }

    public function assessmentOpinion()
    {
        return $this->hasOne(File::class, 'id_object', 'id')
            ->where('code_object', '=', File::CODE_OBJ_LEGISLATIVE_PROGRAM)
            ->where('doc_type', '=', DocTypesEnum::PC_IMPACT_EVALUATION_OPINION);
    }

    public function getTableData()
    {
        return DB::select(
            'select
                        legislative_program_row.month,
                        legislative_program_row.row_num,
                        json_agg(json_build_object(\'id\', legislative_program_row.id, \'value\', legislative_program_row.value, \'type\', dynamic_structure_column.type)) as columns
                    from legislative_program_row
                    join dynamic_structure_column on dynamic_structure_column.id = legislative_program_row.dynamic_structures_column_id
                    where
                        legislative_program_row.legislative_program_id = '.(int)$this->id.'
                        and legislative_program_row.deleted_at is null
                    group by legislative_program_row.month, legislative_program_row.row_num
                    order by legislative_program_row.month, legislative_program_row.row_num asc
                ');
    }
}
