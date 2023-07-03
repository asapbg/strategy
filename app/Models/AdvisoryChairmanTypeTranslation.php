<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvisoryChairmanTypeTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'advisory_chairman_type_id', 'name'];
}
