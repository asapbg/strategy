<?php

namespace Database\Seeders;

use App\Enums\AdvisoryTypeEnum;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMember;
use App\Models\AdvisoryBoardMemberTranslation;
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

        $old_members_db = DB::connection('old_strategy')->select('SELECT * FROM councilmembers');

        $advisory_board_ids = AdvisoryBoard::select('id')->pluck('id')->toArray();

        AdvisoryBoardMember::truncate();

        foreach ($old_members_db as $member) {
            if (!in_array($member->councilID, (array)$advisory_board_ids)) {
                $skipped++;
                continue;
            }

            $new_member = new AdvisoryBoardMember();
            $new_member->id = $member->memberID;
            $new_member->advisory_board_id = $member->councilID;
            $new_member->advisory_type_id = $this->determineAdvisoryType($member->positionOther);
            $new_member->save();

            foreach (config('available_languages') as $language) {
                $translation = new AdvisoryBoardMemberTranslation();
                $translation->locale = $language['code'];
                $translation->advisory_board_member_id = $new_member->id;
                $translation->member_name = $member->name ?? '';
                $translation->member_job = $member->positionOther;
                $translation->save();
            }

            $imported++;
        }

        $this->command->info("$imported advisory board members were imported successfully at " . date("H:i") . " and $skipped were skipped.");
    }

    private function determineAdvisoryType(string|null $position): int
    {
        if (str_contains($position, "секретар")) {
            return AdvisoryTypeEnum::SECRETARY->value;
        }

        if (str_contains($position, "заместник") || str_contains($position, "зам.")) {
            return AdvisoryTypeEnum::VICE_CHAIRMAN->value;
        }

        if (str_contains($position, "министър") || str_contains($position, "председател")) {
            return AdvisoryTypeEnum::CHAIRMAN->value;
        }

        return AdvisoryTypeEnum::MEMBER->value; // член
    }
}
