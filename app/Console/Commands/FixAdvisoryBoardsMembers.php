<?php

namespace App\Console\Commands;

use App\Models\AdvisoryBoard;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Command\Command as CommandAlias;

class FixAdvisoryBoardsMembers extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:adv_board_chairman';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix chairman';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $positions = [];
        $this->comment("Fix advisory board members begins at " . date("H:i"));
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
            $positions[] = $board->positionOther;
            if (in_array($board->councilID, $advisory_board_ids)) {
                AdvisoryBoard::where('id', $board->councilID)->update(['advisory_chairman_type_id' => $this->determineChairmanType($board->positionOther)]);
            }
        }

        if(sizeof($positions)) {
            $fp = fopen('adv_board_positions.csv', 'w');
            foreach ($positions as $fields) {
                fputcsv($fp, [$fields]);
            }
            fclose($fp);
        }

        $this->comment("Fix  advisory board members were imported successfully at " . date("H:i"));
        return CommandAlias::SUCCESS;
    }

    private function determineChairmanType(string|null $position): int
    {
        if (str_contains($position, "съветник")) {
            return 4;
        }
        if (str_contains($position, "министър-председател на Република България") || str_contains($position, 'министър-председателят на Република България') || str_contains($position, 'Министър-председател на Република България')) {
            return 1;
        }

        if (str_contains($position, "заместник министър-председател") || str_contains($position, 'зам.министър председателят') || str_contains($position, 'ЗАМЕСТНИК МИНИСТЪР-ПРЕДСЕДАТЕЛ')  || str_contains($position, 'Заместник министър-председател')) {
            return 2;
        }

        if (str_contains($position, "заместник-министър на ") || str_contains($position, "заместник-министър") || str_contains($position, "зам.министър на") || str_contains($position, "заместник министър на") || str_contains($position, "заместник-министрите") || str_contains($position, "зам. министър на") || str_contains($position, "Заместник-министър на") || str_contains($position, "заместник-министър  на") || str_contains($position, "МИНИСТЪР НА") || str_contains($position, "ЗАМЕСТНИК-МИНИСТЪР НА") || str_contains($position, "зам.министър-председател по") || str_contains($position, "заместник -министър на") || str_contains($position, "заместник - министър на") || str_contains($position, "зам.-министър на") || str_contains($position, "заместник-министърът на")) {
            return 3;
        }

        if (str_contains($position, "министър-председател")) {
            return 1;
        }

        if (str_contains($position, "министър") || str_contains($position, "министърът на") || str_contains($position, "министъра на") || str_contains($position, "Министър на") || str_contains($position, "министър на")) {
            return 3;
        }

        return 4;
    }
}
