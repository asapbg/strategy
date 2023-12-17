<?php

namespace Database\Seeders;

use App\Models\OgpStatus;
use Illuminate\Database\Seeder;

class OgpStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locales = config('available_languages');

        $items = [
            ['name' => 'Чернова', 'css_class' => 'active-ks', 'can_edit' => 1],
            ['name' => 'Активен', 'css_class' => 'active-ks', 'can_edit' => 0],
            ['name' => 'Неактивен', 'css_class' => 'closed-li', 'can_edit' => 0],
//            ['name' => 'Действащ', 'css_class' => 'closed-li', 'can_edit' => 0],
//            ['name' => 'Изпълнен', 'css_class' => 'closed-li', 'can_edit' => 0],
//            ['name' => 'Незапочнало изпълнение', 'css_class' => 'closed-li', 'can_edit' => 0],
//            ['name' => 'Ограничено изпълнение', 'css_class' => 'closed-li', 'can_edit' => 0],
        ];

        foreach ($items as $v) {
            $item = new OgpStatus([
                'active' => 1,
                'css_class' => $v['css_class'],
                'can_edit' => $v['can_edit'],
            ]);
            $item->save();
            if ($item->id) {
                foreach ($locales as $locale) {
                    $item->translateOrNew($locale['code'])->name = $v['name'];
                }
            }
            $item->save();
        }
    }
}
