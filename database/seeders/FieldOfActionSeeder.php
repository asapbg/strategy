<?php

namespace Database\Seeders;

use App\Models\FieldOfAction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class FieldOfActionSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->command->info("Import of field of actions begins at " . date("H:i"));

        $json = File::get(database_path('data/field_of_actions.json'));
        if (!is_json($json)) {
            return;
        }

        $imported = 0;
        $actions = json_decode($json, true);

        foreach ($actions as $action) {
            FieldOfAction::create([
                "name_bg" => $action['name_bg'],
                "name_en" => $action['name_en'],
            ]);

            $imported++;
        }

        $this->command->info("$imported field of actions were imported successfully at " . date("H:i"));
    }
}
