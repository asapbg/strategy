<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StrategicDocumentType;

class StrategicDocumentTypesSeeder extends Seeder
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
            'Стратегия',
            'План за изпълнение',
            'Програма',
            'Концепция',
            'Рамков документ',
            'Други',
            'Национална стратегия',
            'План за действие',
            'Национална програма',
            'Други документи',
        ];

        foreach ($types as $name) {
            $item = new StrategicDocumentType();
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
