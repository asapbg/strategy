<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramProjectTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'program_project_id', 'name'];
}
