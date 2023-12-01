<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 *
 * @method static where(string $string, int $id)
 */
class AdvisoryBoardCustom extends Model
{

    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.advisory_board_custom');
    const TRANSLATABLE_FIELDS = ['body'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;
    public $timestamps = true;

    //activity
    protected string $logName = "advisory_board_custom";

    protected $fillable = ['advisory_board_id', 'title', 'order'];

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'id_object', $this->id)->where('code_object', File::CODE_AB_FUNCTION);
    }

    /**
     * Get the model name
     */
    public function getModelName()
    {
        return $this->name;
    }

    public static function translationFieldsProperties(): array
    {
        return [
            'body' => [
                'type' => 'string',
                'rules' => ['required'],
            ],
        ];
    }
}
