<?php

namespace App\Console\Commands;

use App\Enums\PublicationTypesEnum;
use App\Models\Publication;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class seedOldOgpNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old:ogp_news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old news to OGP module';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Publication::unsetEventDispatcher();
        activity()->disableLogging();
        $locales = config('available_languages');
        $formatTimestamp = 'Y-m-d H:i:s';
        $formatDate = 'Y-m-d';

        //records per query
        $step = 5;
        //max id in old db
        $maxOldId = DB::connection('old_strategy_app')->select('select max(dbo.articles.id) from dbo.articles');
        //start from this id in old database
        $currentStep = 0;

        $ourNews = Publication::withTrashed()->whereNotNull('old_id')->get()->pluck('id', 'old_id')->toArray();
        $ourUsers = User::withTrashed()->where('email', 'not like', '%duplicated-%')->get()->whereNotNull('old_id')->pluck('id', 'old_id')->toArray();

        if( (int)$maxOldId[0]->max ) {
            $maxOldId = (int)$maxOldId[0]->max;
            while ($currentStep < $maxOldId) {
                echo "FromId: ".$currentStep.PHP_EOL;
                $oldDbResult = DB::connection('old_strategy_app')
                    ->select('select
                        a.id as old_id,
                        a.title,
                        a."text" as content,
                        a.date as published_at,
                        a.datecreated as created_at,
                        a.datemodified as updated_at,
                        a.createdbyuserid as old_user_id,
                        a.image as main_image_name,
                        a.isdeleted,
                        a.isactive
                    from dbo.articles a
                    where a.languageid = 1
                        and a.id >= ' . $currentStep . '
                        and a.id < ' . ($currentStep + $step) . '
                    order by a.id ');

                if (sizeof($oldDbResult)) {
                    foreach ($oldDbResult as $item) {
                        DB::beginTransaction();
                        try {
                            if(isset($ourNews[$item->old_id])){
                                //update
                                $oldItem = Publication::withTrashed()->find((int)$ourNews[$item->old_id]);
                                $oldItem->active = $item->isactive && !$item->isdeleted;
                                $oldItem->slug = \Str::slug($item->title);
                                $oldItem->file_id = null;
                                $oldItem->save();
                                foreach ($locales as $locale) {
                                    $oldItem->translateOrNew($locale['code'])->title = $item->title;
                                    $oldItem->translateOrNew($locale['code'])->content = str_replace(['&nbsp;', '&amp;'], '', html_entity_decode($item->content));
                                }
                                $oldItem->save();

                                //TODO migrate files
                                $this->comment('Finish update of old OGP publication with old ID '.$oldItem->old_id);

                            } else{
                                //create
                                $prepareNewItem = [
                                    'old_id' => $item->old_id,
                                    'active' => $item->isactive && !$item->isdeleted,
                                    'type' => PublicationTypesEnum::TYPE_OGP_NEWS->value,
                                    'slug' => \Str::slug($item->title),
                                    'file_id' => null,
                                    'published_at' => !empty($item->published_at) ? Carbon::parse($item->published_at)->format($formatDate) : null,
                                    'created_at' => !empty($item->created_at) ? Carbon::parse($item->created_at)->format($formatTimestamp) : null,
                                    'updated_at' => !empty($item->updated_at) ? Carbon::parse($item->updated_at)->format($formatTimestamp) : null,
                                    'deleted_at' => $item->isdeleted ? Carbon::now()->format($formatTimestamp) : null,
                                    'users_id' => isset($ourUsers[$item->old_user_id]) ? $ourUsers[$item->old_user_id] : null
                                ];

                                $newItem = new Publication();
                                $newItem->fill($prepareNewItem);
                                $newItem->save();
                                foreach ($locales as $locale) {
                                    $newItem->translateOrNew($locale['code'])->title = $item->title;
                                    $newItem->translateOrNew($locale['code'])->content = $item->content;
                                }
                                $newItem->save();

                                //TODO migrate files
                                $this->comment('Finish import of old OGP publication with old ID '.$item->old_id);
                            }
                            DB::commit();
                        } catch (\Exception $e) {
                            Log::error('Migration old startegy OGP publicationand files: ' . $e);
                            DB::rollBack();
                        }
                    }
                }
                $currentStep += $step;
            }
        }
        return Command::SUCCESS;
    }
}
