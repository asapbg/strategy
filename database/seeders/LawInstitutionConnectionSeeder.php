<?php

namespace Database\Seeders;

use App\Models\Law;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class LawInstitutionConnectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get(database_path('data/law_institutions.json'));

        if (!is_json($json)) {
            return;
        }

        $items = json_decode($json, true);

        foreach ($items as $item) {
            $law = Law::whereHas('translation', function ($q) use($item){
                $q->where('name', '=', $item['name']);
            })
                ->where('active', 1)
                ->get()->first();

            if($law) {
                $law->institutions()->sync($item['institutions']);
//                $this->command->info($item['name']. " law connections are updated");
            } else{
                $this->command->error($item['name']. " law not exist");
            }
        }
    }
}
