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


    const ADVISORY_BOARDS_SECTION = 'system_advisory_boards';
    const OGP_LEGISLATIVE_INIT_SECTION = 'legislative_init';
    const FACEBOOK_SECTION = 'facebook';

    const AB_REVIEW_PERIOD_NOTIFY = 'review_period_notify';

    const OGP_SECTION = 'system_ogp';
    const OGP_ADV_BOARD_FORUM = 'adv_board';
    const OGP_FORUM_INFO = 'info_forum';
    const OGP_LEGISLATIVE_INIT_REQUIRED_LIKES = 'required_likes';
    const OGP_LEGISLATIVE_INIT_SUPPORT_IN_DAYS = 'required_support_days';
    const SESSION_LIMIT_KEY = 'session_time_limit';
    const FACEBOOK_IS_ACTIVE = 'fb_active';
    const FACEBOOK_USER_LONG_LIVE_TOKEN = 'user_token_long';
    const FACEBOOK_PAGE_LONG_LIVE_TOKEN = 'page_access_token_long';
    const FACEBOOK_APP_ID = 'app_id';

    /* TYPES */

    const TYPE_SYNC = 'sync';


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

    public function scopeNotEditable($query)
    {
        $query->where('settings.editable', 0);
    }
}
