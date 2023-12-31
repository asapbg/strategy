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
                'name' => 'system_email',
                'type' => 'text',
                'editable' => 1,
                'is_required' => 1
            ],
            [
                'section' => 'system_lp',
                'name' => 'lp_text_bg',
                'type' => 'summernote',
                'editable' => 1,
                'is_required' => 0
            ],
            [
                'section' => 'system_lp',
                'name' => 'lp_text_en',
                'type' => 'summernote',
                'editable' => 1,
                'is_required' => 0
            ],
            [
                'section' => 'system_op',
                'name' => 'op_text_bg',
                'type' => 'summernote',
                'editable' => 1,
                'is_required' => 0
            ],
            [
                'section' => 'system_op',
                'name' => 'op_text_en',
                'type' => 'summernote',
                'editable' => 1,
                'is_required' => 0
            ]
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
