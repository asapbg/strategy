<?php

namespace App\Models;

use App\Models\StrategicDocuments\Institution;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    $id
 * @property int    $advisory_board_id
 * @property int    $advisory_type_id
 * @property int    $advisory_chairman_type_id
 * @property string $name
 *
 * @method static find(bool|float|int|string|null $get)
 * @method static truncate()
 * @method static select(string $string)
 */
class AdvisoryBoardMember extends Model
{

    use FilterSort, SoftDeletes, Translatable;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.advisory_board_members');
    const TRANSLATABLE_FIELDS = ['member_name', 'member_job', 'member_notes'];
    const CHANGEABLE_FIELDS = ['advisory_type_id', 'email', 'institution_id', 'is_advisory_board_member'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;
    public $timestamps = true;

    //activity
    protected string $logName = "advisory_board_members";

    protected $fillable = ['advisory_board_id', 'advisory_type_id', 'email', 'institution_id', 'is_advisory_board_member'];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function consultationLevel(): BelongsTo
    {
        return $this->belongsTo(ConsultationLevel::class);
    }

    public function advisoryChairmanType(): BelongsTo
    {
        return $this->belongsTo(AdvisoryChairmanType::class);
    }

    /**
     * Get the model name
     */
    public function getModelName()
    {
        return $this->member_name;
    }

    public static function translationFieldsProperties(): array
    {
        return [
            'member_name' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:255'],
                'required_all_lang' => true
            ],
            'member_job' => [
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:255'],
                'required_all_lang' => false
            ],
            'member_notes' => [
                'type' => 'summernote',
                'rules' => ['nullable', 'string'],
                'required_all_lang' => false
            ],
        ];
    }
}
