<?php

namespace App\Console\Commands;

use App\Enums\PublicationTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageFileUploadRequest;
use App\Models\File;
use App\Models\Publication;
use App\Models\PublicationCategory;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MigrateNewsAndPublications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old:news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate news and publications from old database with their categories';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        activity()->disableLogging();


        DB::statement('TRUNCATE publication_category CASCADE');
        DB::statement('TRUNCATE publication CASCADE');

        $languages = config('available_languages');

        DB::beginTransaction();

        $this->info("Migrating of news begins at ". date("H:i"));

        $controller = new Controller(new Request());
        $upload_dir = File::PUBLICATION_UPLOAD_DIR;
        $type = PublicationTypesEnum::TYPE_NEWS->value;

        $cat_inserts = 0;
        $news_inserts = 0;

        $newsCategories = DB::connection('old_strategy_app')
            ->select("
                SELECT id,name,datecreated,datemodified
                  FROM newscategories
                 WHERE isdeleted = FALSE AND isactive = TRUE AND isapproved = TRUE AND languageid = '1'
                 LIMIT 1
            ");

        foreach ($newsCategories as $newsCategory) {

            $newscategoryid = $newsCategory->id;

            $cat_inserts++;

            $cat_array = [
                'type' => $type,
                'created_at' => databaseDateTime($newsCategory->datecreated),
                'updated_at' => databaseDateTime($newsCategory->datemodified)
            ];
            $category = new PublicationCategory();
            $category->fill($cat_array);
            $category->save();

            foreach ($languages as $lang) {
                $category->translateOrNew($lang['code'])->name = $newsCategory->name;
            }
            $category->save();

            $news = [];
            $news = DB::connection('old_strategy_app')
                ->select("
                    SELECT id,title,text,imagepath,date as published_at,datecreated as created_at,datemodified as updated_at
                      FROM news
                     WHERE isdeleted = FALSE AND isactive = TRUE AND isapproved = TRUE AND languageid = '1'
                       AND newscategoryid = '$newscategoryid'
                     LIMIT 20
                        --AND id = '10681'
                ");

            if (count($news) == 0) {
                continue;
            }

            foreach ($news as $row) {

                //dd($row);
                $news_id = $row->id;
                $title = $row->title;
                $text = str_replace(["&lt;","&gt;",'&amp;','&middot;'], ["<",'>','&','·'], $row->text);

                if (empty($title) && empty($text)) {
                    continue;
                }
                $news_row['type'] = $type;
                $news_row['slug'] = Str::slug($title);
                $news_row['publication_category_id'] = $category->id;
                $news_row['published_at'] = $row->published_at;
                $news_row['created_at'] = $row->created_at;
                $news_row['updated_at'] = $row->updated_at;

                $news = new Publication();
                $news->fill($news_row);
                $news->save();

                dump($row->imagepath);
                $copy_from = base_path('oldimages'.DIRECTORY_SEPARATOR.'News'.DIRECTORY_SEPARATOR.$row->imagepath);
                $to = base_path('public' .DIRECTORY_SEPARATOR. 'files'.DIRECTORY_SEPARATOR .$upload_dir. $row->imagepath);

                dd($copy_from);
                if (file_exists($copy_from)) {
                    $copied_file = \Illuminate\Support\Facades\File::copy($copy_from, $to);

                    if ($copied_file) {
                        $langReq = new LanguageFileUploadRequest();
                        $newFile = $controller->uploadFileLanguages($langReq, $news->id, File::CODE_OBJ_PUBLICATION, false);
                        $news->file_id = $newFile->id;
                    }
                }

                foreach ($languages as $lang) {
                    $news->translateOrNew($lang['code'])->title = $title;
                    $news->translateOrNew($lang['code'])->short_content = (!empty($text)) ? Str::limit($text, 300) : null;
                    $news->translateOrNew($lang['code'])->content = $text;
                }
                $news->save();

                /**
                 * tabletype = 1 / New, tabletype = 2 / Publications
                 */
                $old_files = DB::connection('old_strategy_app')
                    ->select("
                        SELECT folderid,name,description,datecreated as created_at,datemodified as updated_at
                          FROM used_files as uf
                    INNER JOIN files ON files.id = uf.fileid
                         WHERE tabletype = '1' AND recordid = '$news_id' AND
                               isdeleted = FALSE AND isactive = TRUE AND isapproved = TRUE
                    ");

                if (count($old_files) > 0) {

                    foreach ($old_files as $old_file) {

                        $copy_from = base_path('oldfiles'.DIRECTORY_SEPARATOR.'Folder_'. $old_file->folderid.DIRECTORY_SEPARATOR.$old_file->name);
                        $to = base_path('public' . DIRECTORY_SEPARATOR . 'files'. DIRECTORY_SEPARATOR .$upload_dir.$old_file->name);

                        if (file_exists($copy_from)) {
                            $copied_file = \Illuminate\Support\Facades\File::copy($copy_from, $to);

                            if ($copied_file) {
                                $langReq = new LanguageFileUploadRequest();
                                $controller->uploadFileLanguages($langReq, $news->id, File::CODE_OBJ_PUBLICATION, false);
                            }
                        }

                    }
                }

                $news_inserts++;

            }
        }

        $news_count = $news_inserts / 2; // because of languages
        $this->info("$cat_inserts news categories and $news_count news was migrated successfully at ". date("H:i"));

        DB::commit();
    }
}
