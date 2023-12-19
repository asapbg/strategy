<?php

use App\Enums\OgpStatusEnum;
use App\Models\OgpStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!OgpStatus::count()) {
            $locales = config('available_languages');

            $items = [
                ['name' => 'Чернова', 'css_class' => 'closed-li', 'type' => OgpStatusEnum::DRAFT->value, 'can_edit' => 0],
                ['name' => 'Действащ', 'css_class' => 'active-ks', 'type' => OgpStatusEnum::ACTIVE->value, 'can_edit' => 0],
                ['name' => 'В разработка', 'css_class' => 'closed-li', 'type' => OgpStatusEnum::IN_DEVELOPMENT->value, 'can_edit' => 1],
                ['name' => 'Финализирай план', 'css_class' => 'closed-li', 'type' => OgpStatusEnum::FINAL->value, 'can_edit' => 0],
            ];

            foreach ($items as $v) {
                $exist = OgpStatus::whereTranslation('name', $v['name'])->count();

                if($exist == 0) {
                    $item = new OgpStatus([
                        'active' => 1,
                        'css_class' => $v['css_class'],
                        'can_edit' => $v['can_edit'],
                        'type' => $v['type'],
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

            OgpStatus::whereTranslation('name', 'Неактивен')->delete();
            OgpStatus::whereTranslation('name', 'Активен')->delete();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
