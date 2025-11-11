<?php

namespace App\Console\Commands;

use App\Models\PrisChangePris;
use DateTime;
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
            ->whereNotNull('pris_id')
            ->whereNotNull('changed_pris_id')
            ->whereNull('connect_text')
            //->whereIn('id', [26999])
            //->take(850)
            ->get();

        //dd($connections->toArray());
        foreach ($connections as $connection) {

            if (preg_match('/(?:от|дата)\s*(\d{2}\/\d{2}\/\d{2,4}|\d{4})/', $connection->full_text, $matches)) {
                $date = trim($matches[1]);
                $before = substr($connection->full_text, 0, strpos($connection->full_text, $matches[0]));
                $connect_text = trim(substr($connection->full_text, strpos($connection->full_text, $matches[0]) + strlen($matches[0])));

                if (preg_match('/^(\d{2})\/(\d{2})\/(\d{2,4})$/', $date, $date_parts)) {
                    $day = $date_parts[1];
                    $month = $date_parts[2];
                    $year = $date_parts[3];
                    if (strlen($year) == 2) {
                        $year = (int)$year + 2000; // например, 96 -> 1996
                    }
                    $dt = DateTime::createFromFormat('d/m/Y', "$day/$month/$year");
                    if ($dt && $dt->format('d/m/Y') === "$day/$month/$year") {
                        // валидна дата
                    } else {
                        // невалидна дата, не вземаме
                        $date = '';
                    }
                }

                if ($date !== '') {
                    if (!empty($connect_text)) {
                        if (str_starts_with($connect_text, 'г.')) {
                            $connect_text = trim(substr($connect_text, 2));
                        }
                        if (str_starts_with($connect_text, 'г.)') || str_starts_with($connect_text, 'г./')) {
                            $connect_text = trim(substr($connect_text, 3));
                        }
                        if (str_starts_with($connect_text, 'година на обнародването')) {
                            $connect_text = trim(str_replace("година на обнародването", "", $connect_text));
                        }
                        if (str_starts_with($connect_text, ',') || str_starts_with($connect_text, 'г')) {
                            $connect_text = trim(substr($connect_text, 1));
                        }
                        if (empty($connect_text) || mb_strlen($connect_text) < 4) {
                            continue;
                        }
                        PrisChangePris::where('pris_id', $connection->pris_id)
                            ->where('changed_pris_id', $connection->changed_pris_id)
                            ->where('full_text', $connection->full_text)
                            ->update(['connect_text' => $connect_text]);
                        $this->info("$before | $date | $connect_text");
                    }
                }
            }
        }
        return Command::SUCCESS;
    }
}
