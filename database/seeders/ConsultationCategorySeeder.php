<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConsultationCategory;

class ConsultationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locales = config('available_languages');

        $levels = ['Централно ниво', 'Централно друго', 'Областно ниво', 'Общинско ниво'];

        foreach ($levels as $name) {
            $item = new ConsultationCategory();
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
