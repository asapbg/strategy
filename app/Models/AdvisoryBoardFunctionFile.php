<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    use SoftDeletes;

    protected $fillable = ['file_name', 'file_description'];

    protected function downloadLink(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value = public_path('files/advisory-boards/functions/' . $this->storage->filename),
        );
    }

    public function storage(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
