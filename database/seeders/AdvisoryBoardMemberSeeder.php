<?php

namespace Database\Seeders;

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMember;
use App\Models\AdvisoryBoardTranslation;
use DB;
use Illuminate\Database\Seeder;

/**
 * SQL used for generating the json export:
 *
 * select
 * councils."councilID",
 * councilattribs."category",
 * g."name",
 * councilattribs."institutionType",
 * councilattribs."actType",
 * (
 * select
 * councilmembers."positionOther"
 * from
 * councilmembers
 * where
 * councils."councilID" = councilmembers."councilID"
 * limit 1),
 * councilattribs."requiredSessionsCount",
 * councils.active
 * from
 * councils
 * inner join councilattribs on
 * councils."councilID" = councilattribs."councilID"
 * inner join group_ g on
 * councils."groupID" = g."groupId"
 */
class AdvisoryBoardMemberSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->command->info("Import of advisory board members begins at " . date("H:i"));

        $imported = 0;
        $skipped = 0;

        $old_members_db = DB::connection('old_db')->select('SELECT * FROM councilmembers LIMIT 100');

        $advisory_board_ids = AdvisoryBoard::select('id')->pluck('id');

        foreach ($old_members_db as $member) {
            if (!in_array($member->councilID, (array)$advisory_board_ids)) {
                $skipped++;
                continue;
            }

            $new_member = new AdvisoryBoardMember();
            $new_member->advisory_board_id = $member->councilID;


            $imported++;
        }

        $this->command->info("$imported advisory board members were imported successfully at " . date("H:i") . " and $skipped were skipped.");
    }

    private function determineAdvisoryType(string|null $position): int
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
}
