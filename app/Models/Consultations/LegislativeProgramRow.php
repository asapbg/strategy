<?php

namespace App\Models\Consultations;

use App\Enums\DocTypesEnum;
use App\Models\DynamicStructureColumn;
use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LegislativeProgramRow extends Model
{
    use SoftDeletes;

    public $timestamps = true;
    protected $table = 'legislative_program_row';
    protected $guarded = [];

    public function details(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DynamicStructureColumn::class, 'id', 'dynamic_structures_column_id');
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(LegislativeProgram::class, 'legislative_program_id', 'id');
    }
}
