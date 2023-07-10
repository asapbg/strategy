<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationDocumentTypeTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'consultation_document_type_id', 'name'];
}
