<?php

namespace Database\Seeders;

use App\Enums\DocTypesEnum;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMeeting;
use App\Models\AdvisoryBoardMeetingTranslation;
use App\Models\File;
use App\Services\AdvisoryBoard\AdvisoryBoardFileService;
use App\Services\FileOcr;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;

class AdvisoryBoardMeetingsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->command->info("Import of advisory board meetings begins at " . date("H:i"));

        $imported = 0;
        $skipped = 0;
        $files_imported = 0;

        $old_meetings_db = DB::connection('old_strategy')->select(
            "select
                        c.\"sessionID\",
                        c.\"councilID\",
                        c.\"sessionDate\",
                        details.title,
                        details.description,
                        details.\"folderID\"
                    from
	                    councilsessions c
                    inner join councilsessiondetails details on
	                    c.\"sessionID\" = details.\"sessionID\""
        );

        $advisory_board_ids = AdvisoryBoard::select('id')->pluck('id')->toArray();

        foreach ($old_meetings_db as $meeting) {
            if (!in_array($meeting->councilID, $advisory_board_ids)) {
                $skipped++;
                continue;
            }

            $is_new = false;

            $record = AdvisoryBoardMeeting::find($meeting->sessionID);
            if (!$record) {
                $record = new AdvisoryBoardMeeting();
                $record->id = $meeting->sessionID;
                $record->advisory_board_id = $meeting->councilID;
                $record->next_meeting = Carbon::parse($meeting->sessionDate);
                $record->save();

                $imported++;
                $is_new = true;
            }

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR .
                $meeting->councilID . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_MEETINGS_AND_DECISIONS_UPLOAD_DIR
            );
            mkdirIfNotExists($directory);

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR .
                $meeting->councilID . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_MEETINGS_AND_DECISIONS_UPLOAD_DIR . DIRECTORY_SEPARATOR . $record->id
            );
            mkdirIfNotExists($directory);

            $directory_to_copy_from = base_path('document_library' . DIRECTORY_SEPARATOR . '10108' . DIRECTORY_SEPARATOR . $meeting->folderID);
            $copied_files = copyFiles($directory_to_copy_from, $directory, $meeting->folderID);

            if (!empty($copied_files)) {
                $old_meeting_files_db = DB::connection('old_strategy')->select("select * from dlfileentry d where d.\"folderId\" = $meeting->folderID");

                $service = app(AdvisoryBoardFileService::class);

                foreach ($copied_files as $file) {
                    foreach (config('available_languages') as $lang) {
                        $file_record = $service->storeDbRecord(
                            $record->id,
                            File::CODE_AB,
                            $file['filename'],
                            DocTypesEnum::AB_MEETINGS_AND_DECISIONS->value,
                            $file['content_type'],
                            $file['path'],
                            $file['version'],
                            getOldFileInformation($file['filename'], $old_meeting_files_db)?->description,
                            getOldFileInformation($file['filename'], $old_meeting_files_db)?->title,
                            $lang['code'],
                            getOldFileInformation($file['filename'], $old_meeting_files_db)?->createDate
                        );

                        $ocr = new FileOcr($file_record->refresh());
                        $ocr->extractText();
                    }

                    $files_imported++;
                }
            }

            if ($is_new) {
                foreach (config('available_languages') as $language) {
                    $translation = new AdvisoryBoardMeetingTranslation();
                    $translation->locale = $language['code'];
                    $translation->advisory_board_meeting_id = $record->id;
                    $translation->description = $secretariat->description ?? '';
                    $translation->save();
                }
            }
        }

        $this->command->info("$imported advisory board meetings were imported successfully at " . date("H:i") . " and $skipped were skipped. Totally $files_imported files imported.");
    }
}
