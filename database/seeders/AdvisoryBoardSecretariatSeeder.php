<?php

namespace Database\Seeders;

use App\Enums\DocTypesEnum;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardSecretariat;
use App\Models\AdvisoryBoardSecretariatTranslation;
use App\Models\File;
use App\Services\AdvisoryBoard\AdvisoryBoardFileService;
use App\Services\FileOcr;
use DB;
use Illuminate\Database\Seeder;

class AdvisoryBoardSecretariatSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->command->info("Import of advisory board secretariats begins at " . date("H:i"));

        $imported = 0;
        $skipped = 0;
        $files_imported = 0;

        $old_secretariats_db = DB::connection('old_strategy')->select("SELECT * FROM councildetails c WHERE c.\"name\" LIKE '%secretariate%'");

        $advisory_board_ids = AdvisoryBoard::select('id')->pluck('id')->toArray();

        $secretariat_ids = AdvisoryBoardSecretariat::select('id')->pluck('id')->toArray();

        foreach ($old_secretariats_db as $secretariat) {
            if (!in_array($secretariat->councilID, $advisory_board_ids) || in_array($secretariat->detailID, $secretariat_ids)) {
                $skipped++;
                continue;
            }

            $new_secretariat = new AdvisoryBoardSecretariat();
            $new_secretariat->id = $secretariat->detailID;
            $new_secretariat->advisory_board_id = $secretariat->councilID;
            $new_secretariat->save();

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR .
                $secretariat->councilID . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_SECRETARIAT_UPLOAD_DIR
            );
            mkdirIfNotExists($directory);

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR .
                $secretariat->councilID . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_SECRETARIAT_UPLOAD_DIR . DIRECTORY_SEPARATOR . $new_secretariat->id
            );
            mkdirIfNotExists($directory);

            $directory_to_copy_from = base_path('document_library' . DIRECTORY_SEPARATOR . '10108' . DIRECTORY_SEPARATOR . $secretariat->folderID);
            $copied_files = copyFiles($directory_to_copy_from, $directory, $secretariat->folderID);

            if (!empty($copied_files)) {
                $service = app(AdvisoryBoardFileService::class);

                foreach ($copied_files as $file) {
                    foreach (config('available_languages') as $lang) {
                        $file_record = $service->storeDbRecord(
                            $new_secretariat->id,
                            File::CODE_AB,
                            $file['filename'],
                            DocTypesEnum::AB_SECRETARIAT->value,
                            $file['content_type'],
                            $file['path'],
                            $file['version'],
                            null,
                            null,
                            $lang['code']
                        );

                        $ocr = new FileOcr($file_record->refresh());
                        $ocr->extractText();
                    }

                    $files_imported++;
                }
            }

            foreach (config('available_languages') as $language) {
                $translation = new AdvisoryBoardSecretariatTranslation();
                $translation->locale = $language['code'];
                $translation->advisory_board_secretariat_id = $new_secretariat->id;
                $translation->description = $secretariat->description ?? '';
                $translation->save();
            }

            $imported++;
        }

        $this->command->info("$imported advisory board secretariats were imported successfully at " . date("H:i") . " and $skipped were skipped. Totally $files_imported files imported.");
    }
}
