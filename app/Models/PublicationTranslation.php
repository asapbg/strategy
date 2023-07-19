<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicationTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'publication_id', 'title', 'content'];
}
