<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTypeTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'document_type_id', 'name'];
}
