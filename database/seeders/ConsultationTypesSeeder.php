<?php
namespace Database\Seeders;

use App\Models\ConsultationType;
use Illuminate\Database\Seeder;

class ConsultationTypesSeeder extends Seeder
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
            'Национални',
            'Областни и общински'
        ];

        foreach ($types as $name) {
            $item = new ConsultationType();
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
