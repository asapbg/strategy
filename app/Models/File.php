<?php

namespace App\Models;

use App\Http\Controllers\Admin\AdvisoryBoard\AdvisoryBoardFileController;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Scout\Searchable;

/**
 * @property string $custom_name
 * @property string $advisoryBoardTab
 * @property string $resolution_council_ministers // Постановление на Министерски съвет
 * @property string $state_newspaper              // Държавен вестник
 * @property Carbon $effective_at                 // В сила от
 */
class File extends ModelActivityExtend
{
    use Searchable;

    public $timestamps = true;

//    Code objects
    const CODE_OBJ_PUBLICATION = 1;
    const CODE_OBJ_LEGISLATIVE_PROGRAM = 2;
    const CODE_OBJ_LEGISLATIVE_PROGRAM_GENERAL = 20;
    const CODE_OBJ_OPERATIONAL_PROGRAM = 3;
    const CODE_OBJ_OPERATIONAL_PROGRAM_GENERAL = 30;
    const CODE_OBJ_PAGE = 4;
    const CODE_OBJ_PRIS = 5;
    const CODE_OBJ_PUBLIC_CONSULTATION = 6;

    /** @var int Advisory Board */
    const CODE_AB = 7;
    const CODE_OBJ_AB_MODERATOR = 8;

    const CODE_OBJ_AB_PAGE = 9;
    const CODE_OBJ_STRATEGIC_DOCUMENT = 10;
    const CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN = 11;
    const CODE_OBJ_OGP = 12;

    //Directories objects
    const PUBLICATION_UPLOAD_DIR = 'publications' . DIRECTORY_SEPARATOR;
    const PAGE_UPLOAD_DIR = 'pages' . DIRECTORY_SEPARATOR;
    const PAGE_UPLOAD_PRIS = 'pris' . DIRECTORY_SEPARATOR;
    const PUBLIC_CONSULTATIONS_UPLOAD_DIR = 'pc' . DIRECTORY_SEPARATOR;
    const PUBLIC_CONSULTATIONS_COMMENTS_UPLOAD_DIR = 'pc' . DIRECTORY_SEPARATOR . 'comments' . DIRECTORY_SEPARATOR;
    const PUBLIC_CONSULTATIONS_POLLS_UPLOAD_DIR = 'pc' . DIRECTORY_SEPARATOR . 'polls' . DIRECTORY_SEPARATOR;
    const ADVISORY_BOARD_UPLOAD_DIR = 'advisory-boards' . DIRECTORY_SEPARATOR;
    const ADVISORY_BOARD_SECRETARIAT_UPLOAD_DIR = 'secretariat';
    const ADVISORY_BOARD_FUNCTION_UPLOAD_DIR = 'functions';
    const ADVISORY_BOARD_REGULATORY_FRAMEWORK_UPLOAD_DIR = 'regulatory-frameworks';
    const ADVISORY_BOARD_REGULATORY_FRAMEWORK_ESTABLISHMENT_UPLOAD_DIR = 'establishments';
    const ADVISORY_BOARD_REGULATORY_FRAMEWORK_ORGANIZATION_RULES_UPLOAD_DIR = 'organization-rules';

    const ADVISORY_BOARD_MEETINGS_AND_DECISIONS_UPLOAD_DIR = 'meetings-and-decisions';
    const ADVISORY_BOARD_MODERATOR_UPLOAD_DIR = 'moderator';
    const ADVISORY_BOARD_CUSTOM_SECTION_UPLOAD_DIR = 'custom-sections';
    const OP_GENERAL_UPLOAD_DIR = 'op'. DIRECTORY_SEPARATOR;
    const LP_GENERAL_UPLOAD_DIR = 'lp'. DIRECTORY_SEPARATOR;
    const OGP_PLAN_UPLOAD_DIR = 'ogp_plan'. DIRECTORY_SEPARATOR;

    const MAX_UPLOAD_FILE_SIZE = 30720;
    const ALLOWED_IMAGES_EXTENSIONS = ['jpeg', 'jpg', 'png'];
    const ALLOWED_FILE_EXTENSIONS = ['doc', 'docx', 'xsl', 'xlsx', 'pdf', 'jpeg', 'jpg', 'png'];
    const ALLOWED_FILE_PRIS = ['doc', 'docx', 'pdf'];
    const ALLOWED_FILE_STRATEGIC_DOC = ['doc', 'docx', 'pdf'];
    const ALLOWED_FILE_LP_OO = ['doc', 'docx', 'pdf'];

    const ALLOWED_FILE_PAGE = ['doc', 'docx', 'pdf', 'xsl', 'xlsx', 'jpeg', 'jpg', 'png', 'gif', 'apng', 'avif', 'webp']; //'image/jpeg', 'image/png', 'image/gif','image/svg+xml', 'image/apng', 'image/avif', 'image/webp'
    const ALL_ALLOWED_FILE_EXTENSIONS = ['doc', 'docx', 'xls', 'xlsx', 'pdf', 'p7s', 'p7m', 'zip', 'rar', '7z', 'jpeg', 'jpg', 'png'];
    const ALL_ALLOWED_FILE_EXTENSIONS_MIMES_TYPE = ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/pdf',
        'application/pkcs7-signature', 'application/pkcs7-mime', 'application/zip', 'application/x-rar-compressed', 'application/x-rar', 'application/x-7z-compressed']; //
    const ALLOWED_FILE_OGP_EVALUATION = ['doc', 'docx', 'xsl', 'xlsx', 'pdf', 'jpeg', 'jpg', 'png'];

    const CONTENT_TYPE_IMAGES = ['jpeg', 'jpg', 'png', 'gif', 'apng', 'avif', 'webp' ];//'image/jpeg', 'image/png', 'image/gif','image/svg+xml', 'image/apng', 'image/avif', 'image/webp'
    protected $guarded = [];

    //activity
    protected string $logName = "files";

    protected static function boot() {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('created_at', 'asc');
        });
    }

    public function getNameAttribute()
    {
        return empty($this->custom_name) ? $this->filename : $this->custom_name;
    }

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
            get: fn() => str_contains($this->content_type, 'image') ? '<img src="' . asset('files/'.str_replace('files'.DIRECTORY_SEPARATOR, '', $this->path)) . '" class="img-thumbnail sm-thumbnail">'
                : (str_contains($this->content_type, 'pdf') ? '<img src="' . asset('img/default_pdf.png') . '" class="img-thumbnail sm-thumbnail">' : '')
        );
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'sys_user');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(File::class, 'parent_id')
            ->where('parent_id', $this->id)
            ->orWhere('id', $this->id);
    }

    public function getModelName(): string
    {
        return empty($this->custom_name) ? $this->filename : $this->custom_name;
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
                16 => '#custom',
                17 => '#moderator',
                default => '#unknown'
            }
        );
    }
}
