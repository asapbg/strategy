<?php

namespace Database\Seeders;

use App\Enums\DocTypesEnum;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardFunction;
use App\Models\AdvisoryBoardFunctionTranslation;
use App\Models\File;
use App\Services\AdvisoryBoard\AdvisoryBoardFileService;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;

class AdvisoryBoardWorkingProgramsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->command->info("Import of advisory board working programs begins at " . date("H:i"));

        $imported = 0;
        $skipped = 0;
        $files_imported = 0;

        $old_programs_db = DB::connection('old_strategy')->select("SELECT * FROM councildetails c WHERE c.\"name\" LIKE '%working program%' and  c.\"toVersion\" is null");
        $advisory_board_ids = AdvisoryBoard::select('id')->pluck('id')->toArray();

        AdvisoryBoardFunction::truncate();

        foreach ($old_programs_db as $program) {
            if (!in_array($program->councilID, $advisory_board_ids)) {
                $skipped++;
                continue;
            }

            $new_program = new AdvisoryBoardFunction();
            $new_program->id = $program->detailID;
            $new_program->advisory_board_id = $program->councilID;
            $new_program->working_year = !$program->toVersion ? Carbon::now()->startOfYear() : null;
            $new_program->save();

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR .
                'advisory-boards' . DIRECTORY_SEPARATOR . $program->councilID . DIRECTORY_SEPARATOR . 'functions'
            );
            mkdirIfNotExists($directory);

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'advisory-boards' . DIRECTORY_SEPARATOR .
                $program->councilID . DIRECTORY_SEPARATOR . 'secretariat' . DIRECTORY_SEPARATOR . $new_program->id
            );
            mkdirIfNotExists($directory);

            $directory_to_copy_from = base_path('document_library' . DIRECTORY_SEPARATOR . '10108' . DIRECTORY_SEPARATOR . $program->folderID);
            $copied_files = copyFiles($directory_to_copy_from, $directory, $program->folderID);

            if (!empty($copied_files)) {
                $service = app(AdvisoryBoardFileService::class);

                foreach ($copied_files as $file) {
                    $service->storeDbRecord(
                        $new_program->id,
                        File::CODE_AB,
                        $file['filename'],
                        DocTypesEnum::AB_FUNCTION->value,
                        $file['content_type'],
                        $file['path'],
                        $file['version']
                    );

                    $files_imported++;
                }
            }

            foreach (config('available_languages') as $language) {
                $translation = new AdvisoryBoardFunctionTranslation();
                $translation->locale = $language['code'];
                $translation->advisory_board_function_id = $new_program->id;
                $translation->description = $program->description ?? '';
                $translation->save();
            }

            $imported++;
        }

        $this->command->info("$imported advisory board working programs were imported successfully at " . date("H:i") . " and $skipped were skipped. Totally $files_imported files imported.");
    }
}
