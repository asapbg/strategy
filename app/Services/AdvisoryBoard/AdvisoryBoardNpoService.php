<?php

namespace App\Services\AdvisoryBoard;

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardFunction;
use App\Models\AdvisoryBoardModeratorInformation;
use App\Models\AdvisoryBoardNpo;
use App\Models\AdvisoryBoardNpoTranslation;
use App\Models\AdvisoryBoardSecretariat;

class AdvisoryBoardNpoService
{

    public function __construct(protected AdvisoryBoard $board)
    {
    }

    /**
     * @param array $names - for both bulgarian and english
     *
     * @return void
     */
    public function storeMember(array $names): void
    {
        $presenter = new AdvisoryBoardNpo();
        $presenter->advisory_board_id = $this->board->id;
        $presenter->save();

        foreach (config('available_languages') as $key => $lang) {
            if (!isset($names[$key - 1])) {
                break;
            }

            $translation = new AdvisoryBoardNpoTranslation();
            $translation->locale = $lang['code'];
            $translation->advisory_board_npo_id = $presenter->id;
            $translation->name = $names[$key - 1];
            $translation->save();
        }
    }

    public function removeCompletely(): void
    {
        foreach ($this->board->npos as $npo) {
            $npo->translations()->delete();
            $npo->delete();
        }
    }
}
