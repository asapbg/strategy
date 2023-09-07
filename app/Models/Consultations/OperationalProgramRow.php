<?php

namespace App\Models\Consultations;

use App\Models\DynamicStructureColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperationalProgramRow extends Model
{
    use SoftDeletes;

    public $timestamps = true;
    protected $table = 'operational_program_row';
    protected $guarded = [];

    public function details(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DynamicStructureColumn::class, 'id', 'dynamic_structures_column_id');
    }
    public function parent(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OperationalProgram::class, 'operational_program_id', 'id');
    }

}
