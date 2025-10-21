<?php

namespace App\Console\Commands;

use App\Models\CustomRole;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\AdvBoardUpToDateCheck;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckAdvBoardDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adv_board:is_actual';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications to all moderators of adv boards to check information';
    private $jobFile = 'ab_actual_info_reminder';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $period = Setting::where('name', '=', Setting::AB_REVIEW_PERIOD_NOTIFY)
            ->where('section', '=', Setting::ADVISORY_BOARDS_SECTION)
            ->first();

        if (!$period || (int)$period->value <= 0) {
            return Command::SUCCESS;
        }

        $lastCheckFile = Storage::disk('local')->get($this->jobFile);
        $needCheck = false;

        if ($lastCheckFile) {
            if (Carbon::now()->format('Y-m-d') > Carbon::parse($lastCheckFile)->addMonths((int)$period->value)->format('Y-m-d')) {
//            if(true){
                $needCheck = true;
            }
        } else {
            $needCheck = true;
        }

        if (!$needCheck) {
            return Command::SUCCESS;
        }

        $users = User::with('moderateAdvisoryBoards', 'moderateAdvisoryBoards.board')
            ->whereHas('roles', function ($q) {
                $q->where('name', '=', CustomRole::MODERATOR_ADVISORY_BOARD);
            })
            ->whereHas('moderateAdvisoryBoards')
            ->get();

        if ($users->count()) {
            foreach ($users as $user) {
                $items = [];
                foreach ($user->moderateAdvisoryBoards as $item) {
                    if ($item->board) {
                        $items[] = $item->board;
                    }
                }
                if (sizeof($items)) {
                    $user->notify(new AdvBoardUpToDateCheck($items));
                }
                sleep(1);
            }
        }

        Storage::disk('local')->put($this->jobFile, Carbon::now()->format('Y-m-d'));

        return Command::SUCCESS;
    }
}
