<?php

namespace App\Services\AdvisoryBoard;

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardFunction;
use App\Models\AdvisoryBoardModeratorInformation;
use App\Models\AdvisoryBoardSecretariat;

class AdvisoryBoardService
{

    public function __construct(protected AdvisoryBoard $board)
    {
    }

    public function createDependencyTables(): void
    {
        AdvisoryBoardSecretariat::create(['advisory_board_id' => $this->board->id]);
        AdvisoryBoardFunction::create(['advisory_board_id' => $this->board->id]);
        AdvisoryBoardModeratorInformation::create(['advisory_board_id' => $this->board->id]);
    }
}
