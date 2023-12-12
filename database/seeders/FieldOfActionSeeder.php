<?php

namespace Database\Seeders;

use App\Models\FieldOfAction;
use App\Models\FieldOfActionTranslation;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

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
            $exist = FieldOfAction::find((int)$action['id']);
            if($exist) { continue; }

            $newItem = [
                'id' => $action['id'],
                'deleted_at' => isset($action['isdeleted']) && $action['isdeleted'] ? Carbon::now() : null,
                'active' => (int)$action['isactive'],
                'parentid' => isset($action['parentid']) ? (int)$action['parentid'] : 0
            ];

            if(isset($action['icon_class'])) {
                $newItem['icon_class'] = $action['icon_class'];
            }
            $field_of_action = new FieldOfAction($newItem);
            $field_of_action->save();

            foreach (config('available_languages') as $locale) {
                $name = $locale['code'] == 'en' && !isset($action[$locale['code']]) ? $action['bg'] : $action[$locale['code']];
                if (empty($name)) {
                    continue;
                }

                $translation = new FieldOfActionTranslation();

                $code = $locale['code'] ?? '';
                $translation->locale = $code;
                $translation->field_of_action_id = $field_of_action->id;
                $translation->name = $name;

                if (isset($action['isdeleted']) && $action['isdeleted']) {
                    $translation->deleted_at = Carbon::now();
                }

                $translation->save();
            }

            $imported++;
        }

        $this->command->info("$imported field of actions were imported successfully at " . date("H:i"));
    }
}
