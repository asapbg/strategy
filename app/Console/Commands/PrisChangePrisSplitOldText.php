<?php

namespace App\Console\Commands;

use App\Models\PrisChangePris;
use Illuminate\Console\Command;

class PrisChangePrisSplitOldText extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pris:connect_text';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $connections = PrisChangePris::orderBy('id')
            ->where('created_at', '<', '2025-11-06 00:00:00')
            //->take(850)
            ->get();
        //dd($connections->toArray());
        foreach ($connections as $connection) {
            if (preg_match('/^(.*?)(\d{4}|\d{2}\/\d{2}\/\d{2})(.*)$/', $connection->full_text, $matches)) {
                $before = trim($matches[1]);
                $date = trim($matches[2]);
                $connect_text = trim($matches[3]);
                if (!empty($connect_text)) {
                    if (str_starts_with($connect_text, ',')) {
                        $connect_text = trim(substr($connect_text, 1));
                    }
                    PrisChangePris::where('pris_id', $connection->pris_id)
                        ->where('changed_pris_id', $connection->changed_pris_id)
                        ->where('full_text', $connection->full_text)
                        ->update(['connect_text' => $connect_text]);
                    $this->info("$before | $date | $connect_text");
                }
            }
        }
        return Command::SUCCESS;
    }
}
