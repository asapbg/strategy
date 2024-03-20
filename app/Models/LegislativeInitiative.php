<?php

namespace App\Models;

use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Models\Consultations\OperationalProgramRow;
use App\Models\StrategicDocuments\Institution;
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

    protected $fillable = ['author_id', 'description', 'law_id', 'cap', 'ready_to_send'];

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

//    public function operationalProgram()
//    {
//        return $this->belongsTo(OperationalProgramRow::class, 'operational_program_id', 'operational_program_id');
//    }

//    public function operationalProgramTitle()
//    {
//        return $this->belongsTo(OperationalProgramRow::class, 'operational_program_id', 'operational_program_id')
//            ->where('dynamic_structures_column_id', config('lp_op_programs.op_ds_col_title_id'));
//    }

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

    public function law(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Law::class, 'id', 'law_id');
    }

    public function institutions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Institution::class, 'legislative_initiative_institution', 'legislative_initiative_id', 'institution_id');
    }

    public function countSupport(): int
    {
        return ($this->likes()->count() - $this->dislikes()->count());
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
