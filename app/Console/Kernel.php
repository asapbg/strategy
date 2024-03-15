<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sync:iisda')->daily();
        $schedule->command('programs:actual')->daily();
        $schedule->command('generate:comments')->everyTenMinutes();

        //moderators adv board check for actual info reminder
        $schedule->command('adv_board:is_actual')->daily();

        //OGP module
        $schedule->command('ogp:to_development')->daily();
        $schedule->command('ogp:ogp:to_final')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
