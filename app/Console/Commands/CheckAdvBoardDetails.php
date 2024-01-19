<?php

namespace App\Console\Commands;

use App\Models\CustomRole;
use App\Models\User;
use App\Notifications\AdvBoardUpToDateCheck;
use Illuminate\Console\Command;

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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::with('moderateAdvisoryBoards', 'moderateAdvisoryBoards.board')
            ->whereHas('roles', function ($q){
                $q->where('name', '=', CustomRole::MODERATOR_ADVISORY_BOARD);
            })
            ->whereHas('moderateAdvisoryBoards')
            ->where('email', '=', 'moderator-advisory-board@asap.bg')
            ->get();

        if($users->count()){
            foreach ($users as $user){
                $items = [];
                foreach ($user->moderateAdvisoryBoards as $item){
                    $items[] = $item->board;
                }
                if(sizeof($items)){
                    $user->notify(new AdvBoardUpToDateCheck($items));
                }
            }
        }
        return Command::SUCCESS;
    }
}
