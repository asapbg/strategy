<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OgpStatusTranslation extends Model
{
    public $timestamps = false;
    protected $table = 'ogp_status_translations';

    protected $fillable = ['locale', 'ogp_status_id', 'name'];
}
