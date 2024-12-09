<?php

namespace Database\Seeders;

use App\Models\AdvisoryBoard\AdvisoryBoardNomenclatureFieldOfAction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AdvisoryBoardNomenclatureFieldOfActionSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->command->info("Import of field of actions begins at " . date("H:i"));

        $json = File::get(database_path('data/advisory_board_nomenclature_field_of_actions.json'));
        $translations = File::get(database_path('data/advisory_board_nomenclature_field_of_action_translations.json'));

        if (!is_json($json) || !is_json($translations)) {
            return;
        }

        $imported = 0;
        $actions = json_decode($json, true);
        $translations = json_decode($translations, true);

        foreach ($actions as $action) {
            $exist = AdvisoryBoardNomenclatureFieldOfAction::withTrashed()->find((int)$action['id']);

            if ($exist) {
                continue;
            }

            $action['created_at'] = Carbon::parse($action['created_at']);
            $action['updated_at'] = Carbon::parse($action['updated_at']);

            if (!empty($action['deleted_at'])) {
                $action['deleted_at'] = Carbon::parse($action['deleted_at']);
            }

            $fieldOfAction = AdvisoryBoardNomenclatureFieldOfAction::create($action);

            foreach ($translations as $translation) {
                if ($translation['advisory_board_nomenclature_field_of_action_id'] != $fieldOfAction->id) {
                    continue;
                }

                $fieldOfAction->translations()->create($translation);
            }

            $imported++;
        }

        $this->command->info("$imported field of actions were imported successfully at " . date("H:i"));
    }
}
