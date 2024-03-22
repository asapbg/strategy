<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LegislativeInitiativeReceiver extends Model
{
    use SoftDeletes;

    public $timestamps = true;
}
