<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PrisSetInArchive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pris:archive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set old record as archive';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \DB::statement('update pris set in_archive = 1 where doc_date <= \'1989-01-01\'');
        return Command::SUCCESS;
    }
}
