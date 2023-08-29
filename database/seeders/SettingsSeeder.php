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
        $settings = [
            'admin' => [
                'admin_email' => 'admin@asap.bg',
            ],
        ];

        foreach ($settings as $section => $entries) {
            foreach ($entries as $key => $value) {
                $item = new Setting(compact('key', 'value', 'section'));
                $item->save();
            }
        }
    }
}
