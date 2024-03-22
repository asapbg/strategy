<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int        $id
 * @property int        $user_id
 *
 * @property Collection $stats
 *
 * @method static findOrFail(\Illuminate\Routing\Route|object|string|null $route)
 */
class LegislativeInitiativeComment extends Model
{

    use SoftDeletes;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function initiative(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->HasOne(LegislativeInitiative::class, 'legislative_initiative_id', 'id');
    }

    public function stats(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LegislativeInitiativeCommentStat::class, 'comment_id');
    }

    public function userHasLike(): bool
    {
        if (auth()->user()) {
            return $this->likes()->where('user_id', auth()->user()->id)->exists();
        }

        return false;
    }

    public function userHasDislike(): bool
    {
        if (auth()->user()) {
            return $this->dislikes()->where('user_id', auth()->user()->id)->exists();
        }

        return false;
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LegislativeInitiativeCommentStat::class, 'comment_id')->where('is_like', true);
    }

    public function dislikes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LegislativeInitiativeCommentStat::class, 'comment_id')->where('is_like', false);
    }

    public function countLikes(): int
    {
        return $this->likes()->count();
    }

    public function countDislikes(): int
    {
        return $this->dislikes()->count();
    }
}
