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

    protected $ourDocs;
    protected $ourUsers;
    protected $directory;
    protected $inserted;
    protected $formatTimestamp;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->formatTimestamp = 'Y-m-d H:i:s';

        $ourFiles = StrategicDocumentFile::withTrashed()->get()->whereNotNull('old_file_id')->pluck('id', 'old_file_id')->toArray();
        $this->ourDocs = StrategicDocument::whereNotNull('old_id')->withTrashed()->get()->pluck('id', 'old_id')->toArray();
        $this->ourUsers = User::whereNotNull('old_id')->withTrashed()->get()->pluck('id', 'old_id')->toArray();

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
            -- check if uf.tabletype should be 4
        order by sd.datecreated desc
        ");

        $this->directory = StrategicDocumentFile::DIR_PATH;
        $this->inserted = [];

        try {

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
                $to = base_path('public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $this->directory . $newName);

                if (!file_exists($copy_from)) {
                    $this->comment('File ' . $copy_from . 'do not exist!');
                    DB::rollBack();
                    continue;
                }

                if (isset($ourFiles[$oldDbFile->file_old_id])) {
                    $this->updateFiles($ourFiles[$oldDbFile->file_old_id], $copy_from, $to, $newName, $oldDbFile);
                } else {
                    $this->createNewFiles($copy_from, $to, $newName, $oldDbFile);
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }

    public function updateFiles(
        $fileId,
        $copy_from,
        $to,
        $newName,
        $oldDbFile
    ) {
        $file = StrategicDocumentFile::withTrashed()->find($fileId);

        $didDelete = Storage::disk('public_uploads')->delete($file->path);

        if ($didDelete) {
            $this->info('Deleted old file in public! with ID: ' . $file->id);
        } else {
            $this->info('Didn\'t delete old file with ID ' . ' in public!');
        }

        $copied_file = \Illuminate\Support\Facades\File::copy($copy_from, $to);

        $contentType = Storage::disk('public_uploads')->mimeType($this->directory . $newName);

        $checkIsMain = $this->ourDocs[$oldDbFile->id] . $file->locale;

        if ($copied_file) {
            $file->update([
                'strategic_document_id' => $this->ourDocs[$oldDbFile->id],
                'old_file_id' =>  $oldDbFile->file_old_id,
                'strategic_document_type_id' => 1,
                'sys_user' => $this->ourUsers[(int)$oldDbFile->old_user_id] ?? null,
                'path' => $this->directory . $newName,
                'filename' => $newName,
                'version' => '1.0',
                'content_type' => $contentType,
                'created_at' => Carbon::parse($oldDbFile->created_at)->format($this->formatTimestamp),
                'updated_at' => Carbon::parse($oldDbFile->updated_at)->format($this->formatTimestamp),
                'is_main' => !in_array($checkIsMain, $this->inserted),
                'visible_in_report' => $oldDbFile->isreportvisible
            ]);

            $this->info('Updated file with ID: ' . $file->id);
        }
    }

    public function createNewFiles(
        $copy_from,
        $to,
        $newName,
        $oldDbFile
    ) {
        $copied_file = \Illuminate\Support\Facades\File::copy($copy_from, $to);

        if ($copied_file) {
            $contentType = Storage::disk('public_uploads')->mimeType($this->directory . $newName);

            foreach (['bg', 'en'] as $code) {
                $checkIsMain = $this->ourDocs[$oldDbFile->id] . $code;

                $doc = StrategicDocumentFile::create([
                    'strategic_document_id' => $this->ourDocs[$oldDbFile->id],
                    'old_file_id' =>  $oldDbFile->file_old_id,
                    'strategic_document_type_id' => 1,
                    'locale' => $code,
                    'sys_user' => $this->ourUsers[(int)$oldDbFile->old_user_id] ?? null,
                    'path' => $this->directory . $newName,
                    'filename' => $newName,
                    'version' => '1.0',
                    'content_type' => $contentType,
                    'created_at' => Carbon::parse($oldDbFile->created_at)->format($this->formatTimestamp),
                    'updated_at' => Carbon::parse($oldDbFile->updated_at)->format($this->formatTimestamp),
                    'is_main' => !in_array($checkIsMain, $this->inserted),
                    'visible_in_report' => $oldDbFile->isreportvisible
                ]);

                $doc->translateOrNew($code)->display_name = $oldDbFile->description;

                $doc->save();

                $this->inserted[] = $checkIsMain;

                $this->info('Saved file with ID: ' . $doc->id);
                DB::commit();
            }
        } else {
            DB::rollBack();
            $this->info('Can\'t copy file!');
        }
    }
}
