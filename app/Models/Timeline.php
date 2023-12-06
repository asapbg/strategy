<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timeline extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'public_consultation_timeline';

    public function object(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo()->withTrashed();
    }

}
