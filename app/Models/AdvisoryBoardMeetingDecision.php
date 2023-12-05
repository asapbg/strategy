<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 */
class AdvisoryBoardMeetingDecision extends Model
{

    use SoftDeletes, Translatable;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.advisory_board_meeting_decisions');
    const TRANSLATABLE_FIELDS = ['decisions', 'suggestions', 'other'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    //activity
    protected string $logName = "advisory_board_meeting_decisions";

    protected $fillable = ['advisory_board_meeting_id', 'date_of_meeting', 'agenda', 'protocol'];

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
            'decisions' => [
                'type' => 'string',
                'rules' => ['nullable'],
            ],
            'suggestions' => [
                'type' => 'string',
                'rules' => ['nullable'],
            ],
            'other' => [
                'type' => 'string',
                'rules' => ['nullable'],
            ],
        ];
    }
}
