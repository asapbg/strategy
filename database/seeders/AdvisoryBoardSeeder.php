<?php

namespace Database\Seeders;

use App\Http\Controllers\CommonController;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardCustom;
use App\Models\AdvisoryBoardEstablishment;
use App\Models\AdvisoryBoardFunction;
use App\Models\AdvisoryBoardMember;
use App\Models\AdvisoryBoardOrganizationRule;
use App\Models\AdvisoryBoardTranslation;
use App\Models\File;
use DB;
use Illuminate\Database\Seeder;

class AdvisoryBoardSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->command->info("Import of advisory boards begins at " . date("H:i"));

        $imported = 0;
        $skipped = 0;

        $old_advisory_boards_db = DB::connection('old_strategy')->select("
            select
                 councils.\"councilID\",
                 councilattribs.\"category\",
                 g.\"name\",
                 councilattribs.\"institutionType\",
                 councilattribs.\"actType\",
                 (
                select
                     councilmembers.\"positionOther\"
                from
                     councilmembers
                where
                     councils.\"councilID\" = councilmembers.\"councilID\"
                limit 1),
                 councilattribs.\"requiredSessionsCount\",
                 councils.active
            from
                 councils
            inner join councilattribs on
                 councils.\"councilID\" = councilattribs.\"councilID\"
            inner join group_ g on
                councils.\"groupID\" = g.\"groupId\"
        ");

        $advisory_board_ids = AdvisoryBoard::select('id')->pluck('id')->toArray();

        foreach ($old_advisory_boards_db as $board) {
            if (in_array($board->councilID, $advisory_board_ids)) {
                $skipped++;
                continue;
            }

            $new_advisory_board = new AdvisoryBoard();
            $new_advisory_board->id = $board->councilID;
            $new_advisory_board->policy_area_id = $board->category == 0 ? 1 : $board->category;
            $new_advisory_board->authority_id = $board->institutionType == 0 ? 1 : $board->institutionType;
            $new_advisory_board->advisory_act_type_id = $board->actType == 0 ? 1 : $board->actType;
            $new_advisory_board->advisory_chairman_type_id = $this->determineChairmanType($board->positionOther);
            $new_advisory_board->meetings_per_year = $board->requiredSessionsCount;
            $new_advisory_board->active = $board->active !== 0;
            $new_advisory_board->public = $new_advisory_board->active;

            $new_advisory_board->save();

            // It's needed for further files.
            $directory = base_path('public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR . $new_advisory_board->id);
            mkdirIfNotExists($directory);

            foreach (config('available_languages') as $locale) {
                $translation = new AdvisoryBoardTranslation();
                $translation->locale = $locale['code'];
                $translation->advisory_board_id = $new_advisory_board->id;
                $translation->name = $board->name ?? '';
                $translation->save();
            }

//            $service = app(AdvisoryBoardService::class, ['board' => $new_advisory_board]);
//            $service->createDependencyTables();

            $imported++;
        }

        $this->command->info("$imported advisory boards were imported successfully at " . date("H:i") . " and $skipped were skipped.");

        // we call all other seeders that depends on advisory boards
        $this->callDependableSeeders();

        $this->callCleanUp();
    }

    private function determineChairmanType(string|null $position): int
    {
        if (str_contains($position, "министър-председател")) {
            return 1;
        }

        if (str_contains($position, "заместник-министър") || str_contains($position, "зам.")) {
            return 2;
        }

        if (str_contains($position, "министър")) {
            return 3;
        }

        return 4;
    }

    private function callDependableSeeders(): void
    {
        $this->call([
            AdvisoryBoardMemberSeeder::class,
            AdvisoryBoardSecretariatSeeder::class,
            AdvisoryBoardWorkingProgramsSeeder::class,
            AdvisoryBoardRegulatoryFrameworkSeeder::class,
            AdvisoryBoardMeetingsSeeder::class,
            AdvisoryBoardCustomSectionsSeeder::class,
        ]);
    }

    private function callCleanUp(): void
    {
        CommonController::fixSequence((new AdvisoryBoard())->getTable());
        CommonController::fixSequence((new AdvisoryBoardMember())->getTable());
        CommonController::fixSequence((new AdvisoryBoardFunction())->getTable());
        CommonController::fixSequence((new AdvisoryBoardOrganizationRule())->getTable());
        CommonController::fixSequence((new AdvisoryBoardEstablishment())->getTable());
        CommonController::fixSequence((new AdvisoryBoardCustom())->getTable());
    }
}
