<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\LegalActType;
use App\Models\Pris;
use Illuminate\Console\Command;

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
        $this->info('Start at: '. date('Y-m-d H:i:s'));
        //Search for transcripts category
        $dbProtocols = Pris::where('pris.legal_act_type_id', '=', LegalActType::TYPE_PROTOCOL)
            ->where('asap_last_version', 1)
//            ->where('id', 162525)
            ->get();

        if(!$dbProtocols->count()){
            return Command::SUCCESS;
        }

        foreach ($dbProtocols as $protocol){

            if(!$protocol->files->count()){
                $this->info('Protocol do not have files');
                continue;
            }

            $deletedFiles = [];
            foreach ($protocol->files as $f){
                if(!in_array($f->id, $deletedFiles)){
                    if(str_contains($f->filename, 'ротокол') || $f->filename == $protocol->doc_num.'.doc'){
                        $duplicated = File::where('id_object', '=', $f->id_object)
                            ->where('code_object', '=', $f->code_object)
                            ->where('filename', '=', $f->filename)
                            ->where('path', '=', $f->path)
                            ->where('locale', '=', $f->locale)
                            ->where('id', '<>', $f->id)
                            ->get()
                            ->pluck('id')
                            ->toArray();
                        if(sizeof($duplicated)){
                            $deletedFiles = array_merge($duplicated, $deletedFiles);
                            \DB::statement('update files set deleted_at = \'2024-06-04 00:00:00\' where id in ('.implode(',', $duplicated).') and deleted_at is null');
                        }
                    }
                }
            }

        }
        $this->info('End at: '. date('Y-m-d H:i:s'));
        return Command::SUCCESS;
    }
}
