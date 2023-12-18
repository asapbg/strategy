<?php

namespace App\Console\Commands;

use App\Models\StrategicDocument;
use App\Models\StrategicDocumentFile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class seedOldStrategicDocumentFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old:sd_files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old Strategy strategic document files to application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $formatTimestamp = 'Y-m-d H:i:s';

        $maxOldId = StrategicDocument::select(DB::raw('MAX(old_id)'))->first()->max ?? 0;

        $ourDocs = StrategicDocument::whereNotNull('old_id')->withTrashed()->get()->pluck('id', 'old_id')->toArray();
        $ourUsers = User::whereNotNull('old_id')->withTrashed()->get()->pluck('id', 'old_id')->toArray();
        $oldDbFiles = DB::connection('old_strategy_app')
            ->select("
        SELECT
            uf.fileid as file_old_id,
            uf.recordid as id,
            f.\"name\" as name,
            f.description,
            case when f.isdeleted = true then 1 else 0 end as deleted,
            case when f.isactive = true then 1 else 0 end as active,
            f.createdbyuserid as old_user_id,
            f.datecreated as created_at,
            f.datemodified as updated_at,
            f.dateexparing as valid_until,
            f.isreportvisible,
            folders.id as folder_id,
            folders.\"name\" as folder_name,
            folders.description as folder_description
        from dbo.strategicdocuments sd
        left join dbo.used_files uf on uf.recordid = sd.id
        left join dbo.files f on f.id = uf.fileid
        left join dbo.filefolders folders on folders.id = f.folderid
        where true
            and sd.languageid = 1
            and f.id is not null
            and folders.id is not null
            and uf.tabletype = 4
            and sd.id > $maxOldId
            -- check if uf.tabletype should be 4
        order by sd.datecreated desc
        ");

        $directory = StrategicDocumentFile::DIR_PATH;

        try {
            $inserted = [];

            foreach ($oldDbFiles as $oldDbFile) {
                DB::beginTransaction();

                $this->info('Beginning the import of file with ID: ' . $oldDbFile->id);

                $info = pathinfo($oldDbFile->name);
                if (isset($info['extension'])) {
                    $newName = str_replace('-', '_', Str::slug(str_replace(' ', '_', $info['filename']), '_')) . '.' . $info['extension'];
                } else {
                    $newName = str_replace('-', '_', Str::slug(str_replace(' ', '_', $info['filename']), '_'));
                }

                $copy_from = base_path('oldfiles' . DIRECTORY_SEPARATOR . 'Folder_' . $oldDbFile->folder_id . DIRECTORY_SEPARATOR . $oldDbFile->name);
                $to = base_path('public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $directory . $newName);

                if (!file_exists($copy_from)) {
                    $this->comment('File ' . $copy_from . 'do not exist!');
                    DB::rollBack();
                    continue;
                }

                $copied_file = \Illuminate\Support\Facades\File::copy($copy_from, $to);

                if ($copied_file) {
                    $contentType = Storage::disk('public_uploads')->mimeType($directory.$newName);

                    foreach (['bg', 'en'] as $code) {
                        $checkIsMain = $ourDocs[$oldDbFile->id] . $code;

                        $doc = StrategicDocumentFile::create([
                            'strategic_document_id' => $ourDocs[$oldDbFile->id],
                            'old_file_id' =>  $oldDbFile->file_old_id,
                            'strategic_document_type_id' => 1,
                            'locale' => $code,
                            'sys_user' => $ourUsers[(int)$oldDbFile->old_user_id] ?? null,
                            'path' => $directory.$newName,
                            'filename' => $newName,
                            'version' => '1.0',
                            'content_type' => $contentType,
                            'created_at' => Carbon::parse($oldDbFile->created_at)->format($formatTimestamp),
                            'updated_at' => Carbon::parse($oldDbFile->updated_at)->format($formatTimestamp),
                            'is_main' => !in_array($checkIsMain, $inserted),
                            'visible_in_report' => $oldDbFile->isreportvisible
                        ]);

                        $doc->translateOrNew($code)->display_name = $oldDbFile->description;

                        $doc->save();

                        $inserted[] = $checkIsMain;

                        $this->info('Saved file with ID: ' . $doc->id);
                        DB::commit();
                    }
                } else {
                    DB::rollBack();
                    $this->info('Can\'t copy file!');
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }
}
