<?php

namespace App\Console\Commands;

use App\Enums\PublicationTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageFileUploadRequest;
use App\Models\File;
use App\Models\Publication;
use App\Models\PublicationCategory;
use App\Models\User;
use App\Services\FileOcr;
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
        $users = User::whereNotNull('old_id')->withTrashed()->get()->pluck('id', 'old_id')->toArray();

        $controller = new Controller(new Request());
        $upload_dir = File::PUBLICATION_UPLOAD_DIR;

        DB::beginTransaction();

        // News
        $this->info("Migrating of news begins at ". date("H:i"));
        $type = PublicationTypesEnum::TYPE_NEWS->value;

        $cat_inserts = 0;
        $news_inserts = 0;

//        $newsCategories = [];
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
                    SELECT id,title,text,imagepath,date as published_at,datecreated as created_at,datemodified as updated_at, createdbyuserid
                      FROM news
                     WHERE isdeleted = FALSE AND isactive = TRUE AND isapproved = TRUE AND languageid = '1'
                       AND newscategoryid = '$newscategoryid'
                     --LIMIT 1
                ");

            if (count($news) == 0) {
                continue;
            }

            foreach ($news as $row) {

                //dd($row);
                $news_id = $row->id;
                $title = $row->title;
                $text = $row->text;

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

                //dump($row->imagepath);
                if (!empty($row->imagepath)) {
                    $copy_from = base_path('oldimages'.DIRECTORY_SEPARATOR.'News'.DIRECTORY_SEPARATOR.$row->imagepath);
                    $to = base_path('public' .DIRECTORY_SEPARATOR. 'files'.DIRECTORY_SEPARATOR .$upload_dir. $row->imagepath);

                    //dd($copy_from);
                    if (file_exists($copy_from)) {
                        $copied_file = true;
                        if (!file_exists($to)) {
                            $copied_file = \Illuminate\Support\Facades\File::copy($copy_from, $to);
                        }

                        $mime_type = mime_content_type($to);
                        if ($copied_file) {
                            $file = new File([
                                'id_object' => $news->id,
                                'code_object' => File::CODE_OBJ_PUBLICATION,
                                'filename' => $row->imagepath,
                                'content_type' => $mime_type,
                                'path' => 'files'.DIRECTORY_SEPARATOR.$upload_dir.$row->imagepath,
                                'sys_user' => $users[(int)$row->createdbyuserid] ?? null,
                            ]);
                            $file->save();

                            if ($file) {
                                $news->file_id = $file->id;
                            }
                        }
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
                        SELECT folderid,name,description,datecreated as created_at,datemodified as updated_at, createdbyuserid
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
                            $copied_file = true;
                            if (!file_exists($to)) {
                                $copied_file = \Illuminate\Support\Facades\File::copy($copy_from, $to);
                            }

                            if ($copied_file) {
                                foreach ($languages as $lang) {

                                    $code = $lang['code'];
                                    $mime_type = mime_content_type($to);

                                    $version = 0;
                                    $newFile = new File([
                                        'id_object' => $news_id,
                                        'code_object' => File::CODE_OBJ_PUBLICATION,
                                        'filename' => $old_file->name,
                                        'content_type' => $mime_type,
                                        'path' => 'files'.DIRECTORY_SEPARATOR.$upload_dir.$old_file->name,
                                        'description_'.$code => $old_file->description,
                                        'sys_user' => $users[(int)$row->createdbyuserid] ?? null,
                                        'locale' => $code,
                                        'version' => ($version + 1).'.0'
                                    ]);
                                    $newFile->save();
                                    $fileIds[] = $newFile->id;
                                    $ocr = new FileOcr($newFile->refresh());
                                    $ocr->extractText();
                                }

                                File::find($fileIds[0])->update(['lang_pair' => $fileIds[1]]);
                                File::find($fileIds[1])->update(['lang_pair' => $fileIds[0]]);
                            }
                        }

                    }
                }

                $news_inserts++;
            }
        }

        $this->info("$cat_inserts news categories and $news_inserts news was migrated successfully at ". date("H:i"));

        //Publications
        $this->info("Migrating of publications begins at ". date("H:i"));

        $controller = new Controller(new Request());
        $type = PublicationTypesEnum::TYPE_LIBRARY->value;

        $cat_inserts = 0;
        $publications_inserts = 0;

        $publicationsCategories = DB::connection('old_strategy_app')
            ->select("
                SELECT id,name,datecreated,datemodified
                  FROM publicationcategories
                 WHERE isdeleted = FALSE AND isactive = TRUE AND isapproved = TRUE AND languageid = '1'
                 LIMIT 1
            ");

        foreach ($publicationsCategories as $publicationsCategory) {

            $publicationcategoryid = $publicationsCategory->id;

            $cat_inserts++;

            $cat_array = [
                'type' => $type,
                'created_at' => databaseDateTime($publicationsCategory->datecreated),
                'updated_at' => databaseDateTime($publicationsCategory->datemodified)
            ];
            $category = new PublicationCategory();
            $category->fill($cat_array);
            $category->save();

            foreach ($languages as $lang) {
                $category->translateOrNew($lang['code'])->name = $publicationsCategory->name;
            }
            $category->save();

            $publications = [];
            $publications = DB::connection('old_strategy_app')
                ->select("
                    SELECT id,title,text,image,date as published_at,datecreated as created_at,datemodified as updated_at, createdbyuserid
                      FROM publications
                     WHERE isdeleted = FALSE AND isactive = TRUE AND isapproved = TRUE AND languageid = '1'
                       AND publicationcategoryid = '$publicationcategoryid'
                     --LIMIT 1
                ");

            if (count($publications) == 0) {
                continue;
            }

            foreach ($publications as $row) {

                //dd($row);
                $publications_id = $row->id;
                $title = $row->title;
                $text = $row->text;

                if (empty($title) && empty($text)) {
                    continue;
                }
                $publications_row['type'] = $type;
                $publications_row['slug'] = Str::slug($title);
                $publications_row['publication_category_id'] = $category->id;
                $publications_row['published_at'] = $row->published_at;
                $publications_row['created_at'] = $row->created_at;
                $publications_row['updated_at'] = $row->updated_at;

                $publications = new Publication();
                $publications->fill($publications_row);
                $publications->save();

                //dump($row->image);
                if (!empty($row->image)) {
                    $copy_from = base_path('oldimages'.DIRECTORY_SEPARATOR.'Publications'.DIRECTORY_SEPARATOR.$row->image);
                    $to = base_path('public' .DIRECTORY_SEPARATOR. 'files'.DIRECTORY_SEPARATOR .$upload_dir. $row->image);

                    //dd($copy_from);
                    if (file_exists($copy_from)) {
                        $copied_file = true;
                        if (!file_exists($to)) {
                            $copied_file = \Illuminate\Support\Facades\File::copy($copy_from, $to);
                        }

                        $mime_type = mime_content_type($to);
                        if ($copied_file) {
                            $file = new File([
                                'id_object' => $publications->id,
                                'code_object' => File::CODE_OBJ_PUBLICATION,
                                'filename' => $row->image,
                                'content_type' => $mime_type,
                                'path' => 'files'.DIRECTORY_SEPARATOR.$upload_dir.$row->image,
                                'sys_user' => $users[(int)$row->createdbyuserid] ?? null,
                            ]);
                            $file->save();

                            if ($file) {
                                $publications->file_id = $file->id;
                            }
                        }
                    }
                }

                foreach ($languages as $lang) {
                    $publications->translateOrNew($lang['code'])->title = $title;
                    $publications->translateOrNew($lang['code'])->short_content = (!empty($text)) ? Str::limit($text, 300) : null;
                    $publications->translateOrNew($lang['code'])->content = $text;
                }
                $publications->save();

                /**
                 * tabletype = 1 / New, tabletype = 2 / Publications
                 */
                $old_files = DB::connection('old_strategy_app')
                    ->select("
                        SELECT folderid,name,description,datecreated as created_at,datemodified as updated_at, createdbyuserid
                          FROM used_files as uf
                    INNER JOIN files ON files.id = uf.fileid
                         WHERE tabletype = '2' AND recordid = '$publications_id' AND
                               isdeleted = FALSE AND isactive = TRUE AND isapproved = TRUE
                    ");

                if (count($old_files) > 0) {

                    foreach ($old_files as $old_file) {

                        $copy_from = base_path('oldfiles'.DIRECTORY_SEPARATOR.'Folder_'. $old_file->folderid.DIRECTORY_SEPARATOR.$old_file->name);
                        $to = base_path('public' . DIRECTORY_SEPARATOR . 'files'. DIRECTORY_SEPARATOR .$upload_dir.$old_file->name);

                        if (file_exists($copy_from)) {
                            $copied_file = true;
                            if (!file_exists($to)) {
                                $copied_file = \Illuminate\Support\Facades\File::copy($copy_from, $to);
                            }

                            if ($copied_file) {
                                foreach ($languages as $lang) {

                                    $code = $lang['code'];
                                    $mime_type = mime_content_type($to);

                                    $version = 0;
                                    $newFile = new File([
                                        'id_object' => $news_id,
                                        'code_object' => File::CODE_OBJ_PUBLICATION,
                                        'filename' => $old_file->name,
                                        'content_type' => $mime_type,
                                        'path' => 'files'.DIRECTORY_SEPARATOR.$upload_dir.$old_file->name,
                                        'description_'.$code => $old_file->description,
                                        'sys_user' => $users[(int)$row->createdbyuserid] ?? null,
                                        'locale' => $code,
                                        'version' => ($version + 1).'.0'
                                    ]);
                                    $newFile->save();
                                    $fileIds[] = $newFile->id;
                                    $ocr = new FileOcr($newFile->refresh());
                                    $ocr->extractText();
                                }

                                File::find($fileIds[0])->update(['lang_pair' => $fileIds[1]]);
                                File::find($fileIds[1])->update(['lang_pair' => $fileIds[0]]);
                            }
                        }

                    }
                }

                $publications_inserts++;
            }
        }

        $this->info("$cat_inserts publications categories and $publications_inserts publications was migrated successfully at ". date("H:i"));

        DB::commit();
    }
}
