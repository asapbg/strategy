<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearTestDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:clear_test_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all test data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        return Command::SUCCESS;
    }
}
