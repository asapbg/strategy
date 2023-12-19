<?php

namespace App\Models;

class FormInput extends ModelActivityExtend
{
    public $timestamps = true;

    protected $table = 'form_input';

    //activity
    protected string $logName = "form_input";

    protected $fillable = ['user_id', 'data', 'form'];

    public function scopeFromInternalUsers($query){
        $query->where('by_admin', '=', 1);
    }

    public function getDataParsedAttribute() {
        return json_decode($this->data, true);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
