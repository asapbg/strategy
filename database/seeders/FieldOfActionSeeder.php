<?php

namespace Database\Seeders;

use App\Models\FieldOfAction;
use App\Models\FieldOfActionTranslation;
use Carbon\Carbon;
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

//        $json = File::get(database_path('data/field_of_actions.json'));
        $json = File::get(database_path('data/old_field_of_actions.json'));

        if (!is_json($json)) {
            return;
        }

        $imported = 0;
        $actions = json_decode($json, true);

        foreach ($actions as $action) {
            $field_of_action = new FieldOfAction([
                'id' => $action['id'],
                'icon_class' => $action['icon_class'],
                'deleted_at' => isset($action['isdeleted']) ? Carbon::now() : null
            ]);
            $field_of_action->save();

            foreach (config('available_languages') as $locale) {
                if (!isset($action[$locale['code']])) {
                    continue;
                }

                $translation = new FieldOfActionTranslation();

                $code = $locale['code'] ?? '';
                $translation->locale = $code;
                $translation->field_of_action_id = $field_of_action->id;
                $translation->name = $action[$code];

                if (isset($action['isdeleted'])) {
                    $translation->deleted_at = Carbon::now();
                }

                $translation->save();
            }

            $imported++;
        }

        $this->command->info("$imported field of actions were imported successfully at " . date("H:i"));
    }
}
