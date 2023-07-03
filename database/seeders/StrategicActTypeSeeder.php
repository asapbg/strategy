<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StrategicActType;

class StrategicActTypeSeeder extends Seeder
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
            'Актове',
            'Решение',
            'Постановление',
            'Протокол',
            'Заповед',
        ];

        foreach ($types as $name) {
            $item = new StrategicActType();
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
