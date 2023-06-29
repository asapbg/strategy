<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrategicDocumentLevelTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'strategic_document_level_id', 'name'];
}
