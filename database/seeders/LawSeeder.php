<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LawSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locales = config('available_languages');
        $csvFile = fopen(base_path("database/data/laws.csv"), "r");
        $firstRow = true;
        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if($firstRow) {$firstRow = false; continue;}
            if(is_array($data) && sizeof($data) == 1) {
                $item = \App\Models\Law::create([]);
                if( $item ) {
                    foreach ($locales as $locale) {
                        $item->translateOrNew($locale['code'])->name = $data[0];
                    }
                }
                $item->save();
                $this->command->info("Law with name ".$data[0]." created successfully");
            }
        }
    }
}
