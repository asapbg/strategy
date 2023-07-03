<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdvisoryChairmanType;

class AdvisoryChairmanTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locales = config('available_languages');

        $types = [
            'Министър-председател',
            'Заместник министър-председател',
            'Министър',
            'Председател на държавна агенция',
            'Друго свободно добавяне в номенклатурата',
        ];

        foreach ($types as $name) {
            $item = new AdvisoryChairmanType();
            $item->save();
            if ($item->id) {
                foreach ($locales as $locale) {
                    $item->translateOrNew($locale['code'])->name = $name;
                }
            }
            $item->save();
        }
    }
}
