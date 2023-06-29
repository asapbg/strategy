<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrategicDocumentTypeTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'strategic_document_type_id', 'name'];
}
