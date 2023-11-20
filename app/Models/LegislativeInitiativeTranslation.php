<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegislativeInitiativeTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'legislative_initiative_id', 'description', 'author'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
