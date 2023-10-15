<?php

namespace App\Models\Consultations;

use Illuminate\Database\Eloquent\Model;

class OperationalProgramTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'operational_program_id', 'name'];
}
