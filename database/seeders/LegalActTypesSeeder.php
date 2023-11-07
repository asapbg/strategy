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
            ['in_pris' => 1, 'name' => 'Постановления'],
            ['in_pris' => 1, 'name' => 'Решения'],
            ['in_pris' => 1, 'name' => 'Протоколни решения'],
            ['in_pris' => 1, 'name' => 'Разпореждания'],
            ['in_pris' => 1, 'name' => 'Протоколи'],
            ['in_pris' => 1, 'name' => 'Стенограми'],
            ['in_pris' => 1, 'name' => 'Заповеди'],
            ['in_pris' => 0, 'name' => 'Архив 1944-1989 г.'],
        ];

        foreach ($types as $type) {
            $item = new LegalActType([
                'in_pris' => $type['in_pris']
            ]);
            $item->save();
            if ($item->id) {
                foreach ($locales as $locale) {
                    $item->translateOrNew($locale['code'])->name = $type['name'];
                }
            }
            $item->save();
        }
    }
}
