<?php

namespace Database\Seeders;

use App\Enums\DocTypesEnum;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardCustom;
use App\Models\AdvisoryBoardCustomTranslation;
use App\Models\File;
use App\Services\AdvisoryBoard\AdvisoryBoardFileService;
use App\Services\FileOcr;
use DB;
use Illuminate\Database\Seeder;

class AdvisoryBoardCustomSectionsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->importFunctions();

        $this->importCustoms();
    }

    private function importCustoms(): void
    {
        $this->command->info("Import of advisory board custom sections begins at " . date("H:i"));

        $imported = 0;
        $skipped = 0;
        $files_imported = 0;

        $old_customs_db = DB::connection('old_strategy')->select(
            "select
                        *
                    from
                        councildetails c
                    where
                        \"toVersion\" is null
                        and name = 'custom'"
        );

        $advisory_board_ids = AdvisoryBoard::select('id')->pluck('id')->toArray();

        $all_sections = AdvisoryBoardCustom::select('id')->pluck('id')->toArray();

        foreach ($old_customs_db as $section) {
            if (in_array($section->detailID, $all_sections)) {
                $skipped++;
                continue;
            }

            if (!in_array($section->councilID, $advisory_board_ids)) {
                $skipped++;
                continue;
            }

            $record = new AdvisoryBoardCustom();
            $record->id = $section->detailID;
            $record->advisory_board_id = $section->councilID;
            $record->order = $section->sortOrder;
            $record->save();

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR .
                $section->councilID . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_CUSTOM_SECTION_UPLOAD_DIR
            );
            mkdirIfNotExists($directory);

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR .
                $section->councilID . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_CUSTOM_SECTION_UPLOAD_DIR . DIRECTORY_SEPARATOR . $record->id
            );
            mkdirIfNotExists($directory);

            $directory_to_copy_from = base_path('document_library' . DIRECTORY_SEPARATOR . '10108' . DIRECTORY_SEPARATOR . $section->folderID);
            $copied_files = copyFiles($directory_to_copy_from, $directory, $section->folderID);

            if (!empty($copied_files)) {
                $old_custom_section_files_db = DB::connection('old_strategy')->select("select * from dlfileentry d where d.\"folderId\" = $section->folderID");

                $service = app(AdvisoryBoardFileService::class);

                foreach ($copied_files as $file) {
                    foreach (config('available_languages') as $lang) {
                        $file_record = $service->storeDbRecord(
                            $record->id,
                            File::CODE_AB,
                            $file['filename'],
                            DocTypesEnum::AB_CUSTOM_SECTION->value,
                            $file['content_type'],
                            $file['path'],
                            $file['version'],
                            getOldFileInformation($file['filename'], $old_custom_section_files_db)?->description,
                            getOldFileInformation($file['filename'], $old_custom_section_files_db)?->title,
                            $lang['code'],
                            getOldFileInformation($file['filename'], $old_custom_section_files_db)?->createDate
                        );

                        $ocr = new FileOcr($file_record->refresh());
                        $ocr->extractText();
                    }

                    $files_imported++;
                }
            }

            foreach (config('available_languages') as $language) {
                $translation = new AdvisoryBoardCustomTranslation();
                $translation->locale = $language['code'];
                $translation->advisory_board_custom_id = $record->id;
                $translation->title = $language['code'] === 'bg' ? $section->title : $section->name;
                $translation->body = $section->description ?? '';
                $translation->save();
            }

            $imported++;
        }

        $this->command->info("$imported advisory board custom sections were imported successfully at " . date("H:i") . " and $skipped were skipped. Totally $files_imported files imported.");
    }

    private function importFunctions(): void
    {
        $this->command->info("Import of advisory board functions as custom section begins at " . date("H:i"));

        $imported = 0;
        $skipped = 0;
        $files_imported = 0;

        $old_functions_db = DB::connection('old_strategy')->select(
            "select
                *
            from
                councildetails c
            where
                \"toVersion\" is null
                and name = 'functions'"
        );

        $advisory_board_ids = AdvisoryBoard::select('id')->pluck('id')->toArray();

        $all_functions = AdvisoryBoardCustom::select('id')->pluck('id')->toArray();

        foreach ($old_functions_db as $section) {
            if (in_array($section->detailID, $all_functions)) {
                $skipped++;
                continue;
            }

            if (!in_array($section->councilID, $advisory_board_ids)) {
                $skipped++;
                continue;
            }

            $record = new AdvisoryBoardCustom();
            $record->id = $section->detailID;
            $record->advisory_board_id = $section->councilID;
            $record->order = $section->sortOrder;
            $record->save();

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR .
                $section->councilID . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_CUSTOM_SECTION_UPLOAD_DIR
            );
            mkdirIfNotExists($directory);

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR .
                $section->councilID . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_CUSTOM_SECTION_UPLOAD_DIR . DIRECTORY_SEPARATOR . $record->id
            );
            mkdirIfNotExists($directory);

            $directory_to_copy_from = base_path('document_library' . DIRECTORY_SEPARATOR . '10108' . DIRECTORY_SEPARATOR . $section->folderID);
            $copied_files = copyFiles($directory_to_copy_from, $directory, $section->folderID);

            if (!empty($copied_files)) {
                $old_custom_section_files_db = DB::connection('old_strategy')->select("select * from dlfileentry d where d.\"folderId\" = $section->folderID");

                $service = app(AdvisoryBoardFileService::class);

                foreach ($copied_files as $file) {
                    foreach (config('available_languages') as $lang) {
                        $file_record = $service->storeDbRecord(
                            $record->id,
                            File::CODE_AB,
                            $file['filename'],
                            DocTypesEnum::AB_CUSTOM_SECTION->value,
                            $file['content_type'],
                            $file['path'],
                            $file['version'],
                            getOldFileInformation($file['filename'], $old_custom_section_files_db)?->description,
                            getOldFileInformation($file['filename'], $old_custom_section_files_db)?->title,
                            $lang['code'],
                            getOldFileInformation($file['filename'], $old_custom_section_files_db)?->createDate
                        );

                        $ocr = new FileOcr($file_record->refresh());
                        $ocr->extractText();
                    }


                    $files_imported++;
                }
            }

            foreach (config('available_languages') as $language) {
                $translation = new AdvisoryBoardCustomTranslation();
                $translation->locale = $language['code'];
                $translation->advisory_board_custom_id = $record->id;
                $translation->title = $language['code'] === 'bg' ? $section->title : $section->name;
                $translation->body = $section->description ?? '';
                $translation->save();
            }

            $imported++;
        }

        $this->command->info("$imported advisory board functions as custom section were imported successfully at " . date("H:i") . " and $skipped were skipped. Totally $files_imported files imported.");
    }
}
