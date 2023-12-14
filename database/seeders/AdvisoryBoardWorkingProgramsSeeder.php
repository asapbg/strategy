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
        $this->importWorkingPrograms();

        $this->importReports();
    }

    private function importReports(): void
    {
        $this->command->info("Import of advisory board working program reports begins at " . date("H:i"));

        $imported = 0;
        $skipped = 0;

        $old_reports_db = DB::connection('old_strategy')->select(
            "
                        SELECT *
                            FROM councildetails c
                        WHERE c.\"name\" LIKE '%report%'
                          and  c.\"toVersion\" is null
                        "
        );

        $advisory_boards = AdvisoryBoard::all();

        foreach ($old_reports_db as $report) {
            $advisory_board = $advisory_boards->first(fn($record) => $record->id === $report->councilID);

            if (!$advisory_board) {
                $skipped++;
                continue;
            }

            if (!$advisory_board->workingProgram) {
                $skipped++;
                continue;
            }

            $working_program = $advisory_board->workingProgram;

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR .
                File::ADVISORY_BOARD_UPLOAD_DIR . $advisory_board->id . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_FUNCTION_UPLOAD_DIR
            );
            mkdirIfNotExists($directory);

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR .
                $advisory_board->id . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_FUNCTION_UPLOAD_DIR . DIRECTORY_SEPARATOR . $working_program->id
            );
            mkdirIfNotExists($directory);

            $directory_to_copy_from = base_path('document_library' . DIRECTORY_SEPARATOR . '10108' . DIRECTORY_SEPARATOR . $report->folderID);
            $copied_files = copyFiles($directory_to_copy_from, $directory, $report->folderID);

            if (!empty($copied_files)) {
                $service = app(AdvisoryBoardFileService::class);

                foreach ($copied_files as $file) {
                    $service->storeDbRecord(
                        $working_program->id,
                        File::CODE_AB,
                        $file['filename'],
                        DocTypesEnum::AB_FUNCTION->value,
                        $file['content_type'],
                        $file['path'],
                        $file['version'],
                        $report->description,
                        $report->title
                    );

                    $imported++;
                }
            }
        }

        $this->command->info("$imported advisory board working program reports were imported successfully at " . date("H:i") . " and $skipped were skipped.");
    }

    private function importWorkingPrograms(): void
    {
        $this->command->info("Import of advisory board working programs begins at " . date("H:i"));

        $imported = 0;
        $skipped = 0;

        $old_programs_db = DB::connection('old_strategy')->select("SELECT * FROM councildetails c WHERE c.\"name\" LIKE '%working program%' and  c.\"toVersion\" is null");
        $advisory_board_ids = AdvisoryBoard::select('id')->pluck('id')->toArray();
        $all_working_program_ids = AdvisoryBoardFunction::select('id')->pluck('id')->toArray();

        foreach ($old_programs_db as $program) {
            if (!in_array($program->councilID, $advisory_board_ids)) {
                $skipped++;
                continue;
            }

            if (in_array($program->detailID, $all_working_program_ids)) {
                $skipped++;
                continue;
            }

            $new_program = new AdvisoryBoardFunction();
            $new_program->id = $program->detailID;
            $new_program->advisory_board_id = $program->councilID;
            $new_program->working_year = Carbon::now()->startOfYear();
            $new_program->save();

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR .
                File::ADVISORY_BOARD_UPLOAD_DIR . $program->councilID . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_FUNCTION_UPLOAD_DIR
            );
            mkdirIfNotExists($directory);

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR .
                $program->councilID . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_FUNCTION_UPLOAD_DIR . DIRECTORY_SEPARATOR . $new_program->id
            );
            mkdirIfNotExists($directory);

            foreach (config('available_languages') as $language) {
                $translation = new AdvisoryBoardFunctionTranslation();
                $translation->locale = $language['code'];
                $translation->advisory_board_function_id = $new_program->id;
                $translation->description = $program->description ?? '';
                $translation->save();
            }

            $imported++;
        }

        $this->command->info("$imported advisory board working programs were imported successfully at " . date("H:i") . " and $skipped were skipped.");
    }
}
