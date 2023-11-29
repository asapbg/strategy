<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvisoryBoardSecretaryCouncil extends Model
{

    use FilterSort, SoftDeletes, Translatable;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.advisory_board_secretary_council');
    const TRANSLATABLE_FIELDS = ['name', 'job', 'notes'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;
    public $timestamps = true;

    //activity
    protected string $logName = "advisory_board_members";

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
            'job' => [
                'type' => 'string',
                'rules' => ['nullable'],
            ],
            'notes' => [
                'type' => 'string',
                'rules' => ['nullable']
            ],
        ];
    }
}
