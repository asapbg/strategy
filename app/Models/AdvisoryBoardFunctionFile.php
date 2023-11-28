<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int  $advisory_board_id
 * @property int  $file_id
 * @property File $storage
 */
class AdvisoryBoardFunctionFile extends Model
{

    use SoftDeletes, Translatable;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.advisory_board_function_files');
    const TRANSLATABLE_FIELDS = ['description'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    //activity
    protected string $logName = "advisory_board_function_files";

    protected $fillable = ['file_name', 'file_description'];

    public function storage(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
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
            'file_name' => [
                'type' => 'string',
                'rules' => ['required'],
            ],
            'file_description' => [
                'type' => 'string',
                'rules' => ['nullable'],
            ],
        ];
    }
}
