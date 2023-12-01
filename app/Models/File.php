<?php

namespace App\Models;

use App\Http\Controllers\Admin\AdvisoryBoard\AdvisoryBoardFileController;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Searchable;

/**
 * @property string $custom_name
 * @property string $advisoryBoardTab
 */
class File extends Model
{
    use SoftDeletes, Searchable;
    public $timestamps = true;

    const CODE_OBJ_PUBLICATION = 1;
    const CODE_OBJ_LEGISLATIVE_PROGRAM = 2;
    const CODE_OBJ_OPERATIONAL_PROGRAM = 3;
    const CODE_OBJ_PAGE = 4;
    const CODE_OBJ_PRIS = 5;
    const CODE_OBJ_PUBLIC_CONSULTATION = 6;

    /** @var int Advisory Board */
    const CODE_AB_FUNCTION = 7;


    const PUBLICATION_UPLOAD_DIR = 'publications'.DIRECTORY_SEPARATOR;
    const PAGE_UPLOAD_DIR = 'pages'.DIRECTORY_SEPARATOR;
    const PAGE_UPLOAD_PRIS = 'pris'.DIRECTORY_SEPARATOR;
    const PUBLIC_CONSULTATIONS_UPLOAD_DIR = 'pc'.DIRECTORY_SEPARATOR;
    const PUBLIC_CONSULTATIONS_COMMENTS_UPLOAD_DIR = 'pc'.DIRECTORY_SEPARATOR.'comments'.DIRECTORY_SEPARATOR;
    const ADVISORY_BOARD_UPLOAD_DIR = 'advisory-boards' . DIRECTORY_SEPARATOR;
    const ADVISORY_BOARD_FUNCTION_UPLOAD_DIR = self::ADVISORY_BOARD_UPLOAD_DIR . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR;

    const ALLOWED_FILE_EXTENSIONS = ['doc', 'docx', 'xsl', 'xlsx', 'pdf', 'jpeg', 'jpg', 'png'];
    const ALLOWED_FILE_PRIS = ['doc', 'docx', 'pdf'];
    protected $guarded = [];

    protected function description(): Attribute
    {
        $field = "description_" . app()->getLocale();

        return Attribute::make(
            get: fn($value, $attributes) => $attributes[$field],
        );
    }

    /**
     * Content
     */
    protected function preview(): Attribute
    {
        return Attribute::make(
            get: fn () => str_contains($this->content_type, 'image') ? '<img src="'.asset($this->path).'" class="img-thumbnail sm-thumbnail">'
                : ( str_contains($this->content_type, 'pdf') ? '<img src="'.asset('img/default_pdf.png').'" class="img-thumbnail sm-thumbnail">' : '' )
        );
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'sys_user');
    }

    /**
     * Get the tab, depending on the doc_type.
     * Used for redirects.
     *
     * @return Attribute
     * @see AdvisoryBoardFileController
     */
    public function advisoryBoardTab(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->doc_type) {
                12 => '#functions',
                13 => '#secretariat',
                14 => '#regulatory',
                15 => '#decisions',
                default => '#unknown'
            }
        );
    }
}
