<?php

namespace App\Console\Commands;

use App\Models\AdvisoryBoard;
use App\Models\File;
use Database\Seeders\AdvisoryBoardSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Symfony\Component\Process\Process;

class SyncAdvisoryBoards extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:advisory-boards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Advisory Boards with the old database, that includes truncating everything about advisory boards + files';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info("Clearing advisory boards...");

        AdvisoryBoard::truncate();

        $this->info("Cleared.");

        $this->info("Clearing all files");

        \Illuminate\Support\Facades\File::deleteDirectory(public_path('files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR));

        $this->info("Files cleared.");

        $this->info("Importing...");

        Artisan::call('db:seed --class=AdvisoryBoardSeeder');

        $this->info("Finished");

        return CommandAlias::SUCCESS;
    }
}
