<?php

namespace App\Models\Consultations;

use App\Enums\DocTypesEnum;
use App\Models\DynamicStructureColumn;
use App\Models\File;
use App\Models\StrategicDocuments\Institution;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LegislativeProgramRow extends Model
{
    use SoftDeletes;

    public $timestamps = true;
    protected $table = 'legislative_program_row';
    protected $guarded = [];

    /**
     * Value
     */
    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? html_entity_decode($value) : $value,
            set: fn (string|null $value) => !empty($value) ?  htmlentities(stripHtmlTags($value)) : $value,
        );
    }

    public function details(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DynamicStructureColumn::class, 'id', 'dynamic_structures_column_id');
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(LegislativeProgram::class, 'id', 'legislative_program_id');
    }

    public function institutions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Institution::class, 'legislative_program_row_institution', 'legislative_program_row_id');
    }
}
