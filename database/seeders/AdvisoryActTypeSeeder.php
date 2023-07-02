<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdvisoryActType;

class AdvisoryActTypeSeeder extends Seeder
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
            'Закон',
            'Постановление на Министерския съвет (на основание чл. 21 от Закона за администрацията)',
            'Заповед на председател на държавна агенция (на основание, чл. 47, ал. 8 от Закона за администрацията)',
            'Акт на друг централен орган',
        ];

        foreach ($types as $name) {
            $item = new AdvisoryActType();
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
