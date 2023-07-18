<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PublicationCategory;

class PublicationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locales = config('available_languages');

        $categories = ['Партньорство за открито управление'];

        foreach ($categories as $category) {
            $item = new PublicationCategory();
            $item->save();
            if ($item->id) {
                foreach ($locales as $locale) {
                    $item->translateOrNew($locale['code'])->name = $category;
                }
            }
            $item->save();
        }
    }
}
