<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ModelActivityExtend;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class DynamicStructure extends ModelActivityExtend
{
    use SoftDeletes;
    public $timestamps = true;
    protected $guarded = [];

    const MODULE_NAME = ('dynamic_structures');
    protected $table = 'dynamic_structure';

    //activity
    protected string $logName = "dynamic_structures";

    public function columns(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DynamicStructureColumn::class, 'dynamic_structure_id', 'id')
            ->orderByRaw('dynamic_structure_groups_id DESC NULLS LAST')
            ->orderBy('ord');
    }

    public function groups(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DynamicStructureGroup::class, 'dynamic_structure_id', 'id')
            ->orderBy('ord');
    }
}
