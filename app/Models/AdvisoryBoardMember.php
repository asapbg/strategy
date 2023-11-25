<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvisoryBoardMember extends Model
{

    use FilterSort, SoftDeletes, Translatable;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.advisory_board_members');
    const TRANSLATABLE_FIELDS = ['name', 'job'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;
    public $timestamps = true;

    //activity
    protected string $logName = "advisory_board_members";

    protected $fillable = ['advisory_board_id', 'advisory_type_id', 'advisory_chairman_type_id', 'consultation_level_id'];

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
        ];
    }
}
