<?php

namespace App\Console\Commands;

use App\Models\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixDuplicatedFileRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
        $groupedFiles = DB::select("
            select
                id_object,
                code_object,
                doc_type,
                filename,
                locale,
                count(*)
            from
                files
            where
                deleted_at is null
                and locale = 'bg'
            group by
                id_object,
                code_object,
                doc_type,
                filename,
                locale
            having count(*) > 1
            order by id_object
        ");
        foreach ($groupedFiles as $grouped) {
            $files = File::selectRaw('id,id_object,code_object,doc_type,filename,locale')
                ->where('id_object', $grouped->id_object)
                ->where('code_object', $grouped->code_object)
                ->where('doc_type', $grouped->doc_type)
                ->where('filename', $grouped->filename)
                ->orderBy('id', 'desc')
                ->get();
            $first_bg = true;
            $first_en = true;
            foreach ($files as $file) {
                if ($file->locale == "bg" && $first_bg) {
                    $first_bg = false;
                    //dump('not deleted');
                    continue;
                }
                if ($file->locale == "en" && $first_en) {
                    $first_en = false;
                    //dump('not deleted');
                    continue;
                }
                $file->delete();
                //dump('file has been deleted');
            }
        }
        return Command::SUCCESS;
    }
}
