<?php

use App\Models\Page;
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
        $translations = [
            'name_bg' => 'Декларация за достъпност',
            'name_en' => 'Accessibility Statement',
            'content_bg' => '',
            'content_en' => '',
        ];

        $item = Page::create([
            'slug' => 'accessibility-statement',
            'system_name' => Page::ACCESS_STATEMENT,
            'is_system' => 1,
        ]);

        $locales = config('available_languages');

        foreach ($locales as $locale) {
            $item->translateOrNew($locale['code'])->name = $translations['name_'.$locale['code']];
            $item->translateOrNew($locale['code'])->content = $translations['content_'.$locale['code']];
        }

        $item->save();
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
