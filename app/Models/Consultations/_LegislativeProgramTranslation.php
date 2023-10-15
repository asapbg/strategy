<?php

namespace App\Models\Consultations;

use Illuminate\Database\Eloquent\Model;

class LegislativeProgramTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'legislative_program_id', 'name'];
}
