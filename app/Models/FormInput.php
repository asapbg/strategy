<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class FormInput extends ModelActivityExtend
{
    public $timestamps = true;

    protected $table = 'form_input';

    //activity
    protected string $logName = "form_input";

    protected $fillable = ['user_id', 'data', 'form'];

    public function getDataParsedAttribute() {
        return json_decode($this->data, true);
    }
}
