<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegulatoryActType;

class RegulatoryActTypesSeeder extends Seeder
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
            'Нормативен акт на Министерския съвет',
        ];

        foreach ($types as $type) {
            $item = new RegulatoryActType();
            $item->save();
            foreach ($locales as $locale) {
                $item->translateOrNew($locale['code'])->name = $type;
            }
            $item->save();
        }
    }
}
