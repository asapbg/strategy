<?php

namespace App\Models;

use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Models\Consultations\OperationalProgramRow;
use App\Traits\FilterSort;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int        $id
 * @property int        $author_id
 *
 * @property Collection $votes
 *
 * @method static find(mixed $legislative_initiative_id)
 */
class LegislativeInitiative extends ModelActivityExtend
{

    use FilterSort;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.nomenclatures.legislative_initiative');

    public $timestamps = true;

    protected $table = 'legislative_initiative';

    //activity
    protected string $logName = "legislative_initiative";

    protected $fillable = ['operational_program_id', 'author_id', 'description'];

    /**
     * Get the model name
     */
    public function getModelName()
    {
        return $this->name;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'description' => [
                'type' => 'summernote',
                'rules' => ['required', 'string']
            ],
        );
    }

    public function operationalProgram()
    {
        return $this->belongsTo(OperationalProgramRow::class, 'operational_program_id', 'operational_program_id');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(LegislativeInitiativeVote::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(LegislativeInitiativeComment::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getStatus(int $value): LegislativeInitiativeStatusesEnum
    {
        return LegislativeInitiativeStatusesEnum::from($value);
    }

    public function setStatus(LegislativeInitiativeStatusesEnum $value): void
    {
        $this->attributes['status'] = $value->value;
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
        return $this->hasMany(LegislativeInitiativeVote::class)->where('is_like', true);
    }

    public function dislikes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LegislativeInitiativeVote::class)->where('is_like', false);
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
