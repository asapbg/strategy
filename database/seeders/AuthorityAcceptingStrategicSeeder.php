<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuthorityAcceptingStrategic;

class AuthorityAcceptingStrategicSeeder extends Seeder
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
            'Министерския съвет',
            'Народното събрание',
            'Областния управител',
            'Общинския съвет',
        ];

        foreach ($types as $name) {
            $item = new AuthorityAcceptingStrategic();
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
