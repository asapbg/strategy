<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuthorityAdvisoryBoard;

class AuthorityAdvisoryBoardSeeder extends Seeder
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
            'Министерски съвет',
            'Министър-председател',
            'Министър',
            'Държавна агенция',
            'Друг централен орган',
        ];

        foreach ($types as $name) {
            $item = new AuthorityAdvisoryBoard();
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
