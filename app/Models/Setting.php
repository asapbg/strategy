<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

class Setting extends ModelActivityExtend
{
    const MODULE_NAME = ('custom.setting');
    protected $guarded = [];
    public $timestamps = true;

//    const SESSION_LIMIT_KEY = 'session_time_limit';
    const CONTACT_MAIL_KEY = 'contact_email';
    const PAGE_CONTENT_OP = 'op_text';
    const PAGE_CONTENT_LP = 'lp_text';
    const PAGE_CONTENT_PC = 'pc_text';
    const PAGE_CONTENT_PRIS = 'pris_text';
    const PAGE_CONTENT_LI = 'li_text';
    const PAGE_CONTENT_STRATEGY_DOC = 'strategy_doc_text';
    const PAGE_CONTENT_ADVISORY_BOARDS = 'advisory_boards_text';

    //activity
    protected string $logName = "settings";

    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? html_entity_decode($value) : $value,
            set: fn (string|null $value) => !empty($value) ?  htmlentities(stripHtmlTags($value)) : $value,
        );
    }
    public function scopeEditable($query)
    {
        $query->where('settings.editable', 1);
    }
}
