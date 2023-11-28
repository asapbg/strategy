<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, mixed $advisory_board_id)
 */
class AdvisoryBoardFunction extends Model
{

    use FilterSort, Translatable;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.advisory_board_functions');
    const TRANSLATABLE_FIELDS = ['description'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;
    public $timestamps = true;

    //activity
    protected string $logName = "advisory_board_functions";

    protected $fillable = ['advisory_board_id'];

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
            'description' => [
                'type' => 'string',
                'rules' => ['required'],
            ],
        ];
    }
}
