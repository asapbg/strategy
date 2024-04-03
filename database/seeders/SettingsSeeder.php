<?php
namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            [
                'section' => 'system_notifications',
                'name' => 'session_time_limit',
                'type' => 'number',
                'value' => 60,
                'editable' => 1,
                'is_required' => 1
            ],
            [
                'section' => 'system_notifications',
                'name' => 'contact_email',
                'type' => 'text',
                'editable' => 1,
                'is_required' => 1
            ],
            [
                'section' => 'system_notifications',
                'name' => 'system_email',
                'type' => 'text',
                'editable' => 1,
                'is_required' => 1
            ],
//            [
//                'section' => 'system_lp',
//                'name' => 'lp_text_bg',
//                'type' => 'summernote',
//                'editable' => 1,
//                'is_required' => 0
//            ],
//            [
//                'section' => 'system_lp',
//                'name' => 'lp_text_en',
//                'type' => 'summernote',
//                'editable' => 1,
//                'is_required' => 0
//            ],
//            [
//                'section' => 'system_op',
//                'name' => 'op_text_bg',
//                'type' => 'summernote',
//                'editable' => 1,
//                'is_required' => 0
//            ],
//            [
//                'section' => 'system_op',
//                'name' => 'op_text_en',
//                'type' => 'summernote',
//                'editable' => 1,
//                'is_required' => 0
//            ],
            [
                'section' => 'system_pc',
                'name' => 'pc_text_bg',
                'type' => 'summernote',
                'editable' => 1,
                'is_required' => 0
            ],
            [
                'section' => 'system_pc',
                'name' => 'pc_text_en',
                'type' => 'summernote',
                'editable' => 1,
                'is_required' => 0
            ],
            [
                'section' => 'system_pris',
                'name' => 'pris_text_bg',
                'type' => 'summernote',
                'editable' => 1,
                'is_required' => 0
            ],
            [
                'section' => 'system_pris',
                'name' => 'pris_text_en',
                'type' => 'summernote',
                'editable' => 1,
                'is_required' => 0
            ],
            [
                'section' => 'system_li',
                'name' => 'li_text_bg',
                'type' => 'summernote',
                'editable' => 1,
                'is_required' => 0
            ],
            [
                'section' => 'system_li',
                'name' => 'li_text_en',
                'type' => 'summernote',
                'editable' => 1,
                'is_required' => 0
            ],
//            [
//                'section' => 'system_strategy_doc',
//                'name' => 'strategy_doc_text_bg',
//                'type' => 'summernote',
//                'editable' => 1,
//                'is_required' => 0
//            ],
//            [
//                'section' => 'system_strategy_doc',
//                'name' => 'strategy_doc_text_en',
//                'type' => 'summernote',
//                'editable' => 1,
//                'is_required' => 0
//            ],
            [
                'section' => 'system_advisory_boards',
                'name' => 'review_period_notify',
                'type' => 'text',
                'editable' => 1,
                'is_required' => 1
            ],
            [
                'section' => 'system_ogp',
                'name' => 'adv_board',
                'type' => 'numeric',
                'editable' => 1,
                'is_required' => 0
            ],
            [
                'section' => 'system_ogp',
                'name' => 'info_forum',
                'type' => 'summernote',
                'editable' => 1,
                'is_required' => 0
            ],
            [
                'section' => 'legislative_init',
                'name' => 'required_likes',
                'type' => 'numeric',
                'editable' => 1,
                'is_required' => 1,
                'value' => 50
            ],
            [
                'section' => 'legislative_init',
                'name' => 'required_support_days',
                'type' => 'numeric',
                'editable' => 1,
                'is_required' => 1,
                'value' => 50
            ],
            [
                'section' => 'facebook',
                'name' => 'fb_active',
                'type' => 'numeric',
                'editable' => 1,
                'is_required' => 0,
                'value' => 0
            ],
            [
                'section' => 'facebook',
                'name' => 'app_id',
                'type' => 'numeric',
                'editable' => 1,
                'is_required' => 0,
                'value' => ''
            ],
            [
                'section' => 'facebook',
                'name' => 'app_secret',
                'type' => 'text',
                'editable' => 1,
                'is_required' => 0,
                'value' => ''
            ],
            [
                'section' => 'facebook',
                'name' => 'user_id',
                'type' => 'numeric',
                'editable' => 1,
                'is_required' => 0,
                'value' => ''
            ],
            [
                'section' => 'facebook',
                'name' => 'user_id',
                'type' => 'numeric',
                'editable' => 1,
                'is_required' => 0,
                'value' => ''
            ],
            [
                'section' => 'facebook',
                'name' => 'user_token',
                'type' => 'text',
                'editable' => 1,
                'is_required' => 0,
                'value' => ''
            ],
            [
                'section' => 'facebook',
                'name' => 'user_token_long',
                'type' => 'text',
                'editable' => 0,
                'is_required' => 0,
                'value' => ''
            ],
            [
                'section' => 'facebook',
                'name' => 'page_id',
                'type' => 'numeric',
                'editable' => 1,
                'is_required' => 0,
                'value' => ''
            ],
            [
                'section' => 'facebook',
                'name' => 'page_access_token',
                'type' => 'text',
                'editable' => 0,
                'is_required' => 0,
                'value' => ''
            ],
            [
                'section' => 'facebook',
                'name' => 'page_access_token_long',
                'type' => 'text',
                'editable' => 0,
                'is_required' => 0,
                'value' => ''
            ],
        );

        foreach ($data as $s) {
            $record = Setting::where('name', $s['name'])->first();

            if ($record) {
                $this->command->line("Setting ".$s['name']." already exists in db");
                continue;
            }

            Setting::create($s);

            $this->command->info("Setting with name ".$s['name']." created successfully");
        }
    }
}
