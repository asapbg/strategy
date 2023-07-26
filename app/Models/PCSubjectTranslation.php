<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PCSubjectTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'pc_subject_translations';


    protected $fillable = ['locale', 'pc_subject_id', 'contractor', 'executor', 'objective', 'description'];
}
