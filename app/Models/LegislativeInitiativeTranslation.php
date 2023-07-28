<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegislativeInitiativeTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'legislative_initiative_id', 'description', 'author'];
}
