<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Translatable;

/**
 * @property int $id
 * @property int $advisory_board_id
 */
class AdvisoryBoardNpo extends ModelActivityExtend
{

    use FilterSort, Translatable;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.advisory_board_npo');
    const TRANSLATABLE_FIELDS = ['name'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;
    public $timestamps = true;

    //activity
    protected string $logName = "advisory_board_npo";

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
            'name' => [
                'type' => 'string',
                'rules' => ['required'],
            ],
        ];
    }
}
