<?php
namespace Database\Seeders;

use App\Models\ActType;
use Illuminate\Database\Seeder;
use App\Models\ConsultationCategory;

class ActTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locales = config('available_languages');

        $types = ['Централно ниво' => [
            'Закон',
            'Акт на Министерския съвет',
            'Акт на министър',
            'Ненормативен акт (на МС или на министър)',
            'Рамкова позиция',
        ],
        'Централно друго' => [
            'Акт на друг централен орган',
            'Ненормативен акт',
            'Рамкова позиция',
        ],
        'Областно ниво' => [
            'Акт на областен управител',
            'Ненормативен акт',
        ],
        'Общинско ниво' => [
            'Акт на общински съвет',
            'Акт на кмет на община',
            'Ненормативен акт',
        ]];

        foreach ($types as $level => $types) {
            $level = ConsultationCategory::whereHas('translations', function($query) use ($level) {
                $query->where('name', $level);
            })->first();
            foreach ($types as $type) {
                $item = new ActType();
                $item->consultation_category_id = $level->id;
                $item->save();
                if ($item->id) {
                    foreach ($locales as $locale) {
                        $item->translateOrNew($locale['code'])->name = $type;
                    }
                }
                $item->save();
            }
        }
    }
}
