<?php
namespace Database\Seeders;

use App\Models\PolicyArea;
use Illuminate\Database\Seeder;

class PolicyAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locales = config('available_languages');

        $areas = [
            'COVID-19',
            'Бизнес среда',
            'Външна политика, сигурност и отбрана',
            'Държавна администрация',
            'Енергетика',
            'Защита на потребителите',
            'Здравеопазване',
            'Земеделие и селски райони',
            'Качество и безопасност на храните',
            'Култура',
            'Междусекторни политики',
            'Младежка политика',
            'Наука и технологии',
            'Образование',
            'Околна среда',
            'Правосъдие и вътрешни работи',
            'Регионална политика',
            'Социална политика и заетост',
            'Спорт',
            'Транспорт',
            'Туризъм',
            'Финанси и данъчна политика',
        ];

        foreach ($areas as $area) {
            $item = new PolicyArea();
            $item->save();
            if ($item->id) {
                foreach ($locales as $locale) {
                    $item->translateOrNew($locale['code'])->name = $area;
                }
            }
            $item->save();
        }
    }
}
