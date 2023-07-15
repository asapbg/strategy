<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrategicDocumentTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'strategic_document_id', 'name'];
}
