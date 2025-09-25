<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\LegalActType;
use App\Models\Pris;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PrisClearDublicatedProtocolFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pris:clear_protocol_duplicated_files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all duplicated files for PRIS records';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Start at: ' . date('Y-m-d H:i:s'));
        //Search for transcripts category
        $dbProtocols = Pris::where('pris.legal_act_type_id', '=', LegalActType::TYPE_PROTOCOL)
            ->where('asap_last_version', 1)
//            ->where('id', 162525)
            ->get();

        if (!$dbProtocols->count()) {
            return Command::SUCCESS;
        }

        foreach ($dbProtocols as $protocol) {

            if (!$protocol->files->count()) {
                $this->info('Protocol do not have files');
                continue;
            }

            $deletedFiles = [];
            foreach ($protocol->files as $file) {
                if (!in_array($file->id, $deletedFiles)) {
                    if (str_contains($file->filename, 'ротокол') || $file->filename == $protocol->doc_num . '.doc') {
                        $duplicated = File::where('id_object', '=', $file->id_object)
                            ->where('code_object', '=', $file->code_object)
                            ->where('filename', '=', $file->filename)
                            ->where('path', '=', $file->path)
                            ->where('locale', '=', $file->locale)
                            ->where('id', '<>', $file->id)
                            ->get()
                            ->pluck('id')
                            ->toArray();
                        if (sizeof($duplicated)) {
                            $deletedFiles = array_merge($duplicated, $deletedFiles);
                            $ids_list = implode(',', $duplicated);
                            $this->comment("Soft deleted files with IDs: $ids_list");
                            DB::statement("update files set deleted_at = '2024-06-04 00:00:00' where id in ($ids_list) and deleted_at is null");
                        }
                    }
                }
            }

        }
        $this->info('End at: ' . date('Y-m-d H:i:s'));
        return Command::SUCCESS;
    }
}
