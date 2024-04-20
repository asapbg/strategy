<?php
namespace Database\Seeders;

use App\Enums\InstitutionCategoryLevelEnum;
use App\Models\ActType;
use Illuminate\Database\Seeder;
use App\Models\ConsultationLevel;

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

        foreach ($types as $level => $acts) {
            switch ($level){
                case 'Централно ниво':
                    $levelId = InstitutionCategoryLevelEnum::CENTRAL->value;
                    break;
                case 'Централно друго':
                    $levelId = InstitutionCategoryLevelEnum::CENTRAL_OTHER->value;
                    break;
                case 'Областно ниво':
                    $levelId = InstitutionCategoryLevelEnum::AREA->value;
                    break;
                case 'Общинско ниво':
                    $levelId = InstitutionCategoryLevelEnum::MUNICIPAL->value;
                    break;
            }

            foreach ($acts as $act) {
                $item = new ActType();
                $item->consultation_level_id = $levelId;
                $item->save();
                if ($item->id) {
                    foreach ($locales as $locale) {
                        $item->translateOrNew($locale['code'])->name = $act;
                    }
                }
                $item->save();
            }
        }
    }
}
