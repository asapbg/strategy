<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LegalActType;

class LegalActTypesSeeder extends Seeder
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
            'Постановления',
            'Решения',
            'Протоколни решения',
            'Разпореждания',
            'Протоколи',
            'Стенограми',
            'Заповеди',
            'Архив 1944-1989 г.'
        ];

        foreach ($types as $name) {
            $item = new LegalActType();
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
