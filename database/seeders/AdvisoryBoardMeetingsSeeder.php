<?php

namespace Database\Seeders;

use App\Enums\DocTypesEnum;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMeeting;
use App\Models\AdvisoryBoardMeetingTranslation;
use App\Models\File;
use App\Services\AdvisoryBoard\AdvisoryBoardFileService;
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

        AdvisoryBoardMeeting::truncate();
        AdvisoryBoardMeetingTranslation::truncate();

        foreach ($old_meetings_db as $meeting) {
            if (!in_array($meeting->councilID, $advisory_board_ids)) {
                $skipped++;
                continue;
            }

            $new_meeting = new AdvisoryBoardMeeting();
            $new_meeting->advisory_board_id = $meeting->councilID;
            $new_meeting->next_meeting = Carbon::parse($meeting->sessionDate);
            $new_meeting->save();

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR .
                $meeting->councilID . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_MEETINGS_AND_DECISIONS_UPLOAD_DIR
            );
            mkdirIfNotExists($directory);

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR .
                $meeting->councilID . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_MEETINGS_AND_DECISIONS_UPLOAD_DIR . DIRECTORY_SEPARATOR . $new_meeting->id
            );
            mkdirIfNotExists($directory);

            $directory_to_copy_from = base_path('document_library' . DIRECTORY_SEPARATOR . '10108' . DIRECTORY_SEPARATOR . $meeting->folderID);
            $copied_files = copyFiles($directory_to_copy_from, $directory, $meeting->folderID);

            if (!empty($copied_files)) {
                $service = app(AdvisoryBoardFileService::class);

                foreach ($copied_files as $file) {
                    $service->storeDbRecord(
                        $new_meeting->id,
                        File::CODE_AB,
                        $file['filename'],
                        DocTypesEnum::AB_MEETINGS_AND_DECISIONS->value,
                        $file['content_type'],
                        $file['path'],
                        $file['version']
                    );

                    $files_imported++;
                }
            }

            foreach (config('available_languages') as $language) {
                $translation = new AdvisoryBoardMeetingTranslation();
                $translation->locale = $language['code'];
                $translation->advisory_board_meeting_id = $new_meeting->id;
                $translation->description = $secretariat->description ?? '';
                $translation->save();
            }

            $imported++;
        }

        $this->command->info("$imported advisory board meetings were imported successfully at " . date("H:i") . " and $skipped were skipped. Totally $files_imported files imported.");
    }
}
