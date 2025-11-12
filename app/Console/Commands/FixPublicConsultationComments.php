<?php

namespace App\Console\Commands;

use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixPublicConsultationComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old:pc_comments';

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
        //file_put_contents('old_pc_comments_fixed', '');
        $pcWithoutComment = PublicConsultation::select('id','old_id')
            ->withCount('commentsWithTrashed')
            ->whereNotNull('old_id')
            //->where('old_id', '>', 9517)
            //->whereDoesntHave('comments')
            ->whereIn('old_id', [9457])
            ->get();

        if ($pcWithoutComment->count() == 0) {
            $this->comment("There are no public consultation without comments");
            return Command::SUCCESS;
        }

        $ourUsers = User::withTrashed()->where('email', 'not like', '%duplicated-%')->whereNotNull('old_id')->get()->pluck('id', 'old_id')->toArray();

        foreach ($pcWithoutComment as $pc) {
            $oldDbComments = DB::connection('old_strategy_app')
                ->select("
                     select pcomments.createdbyuserid as user_id,
                            pcomments.title || '\n' || pcomments.text as content,
                            pcomments.consultationid as object_id,
                            pcomments.datecreated as created_at,
                  case when pcomments.isdeleted = true then CURRENT_TIMESTAMP else null end as deleted_at,
                  case when pcomments.isactive = true then 1 else 0 end as active,
                  case when pcomments.isapproved  = true then 1 else 0 end as approved
                       from dbo.publicconsultationcomments pcomments
                      where pcomments.consultationid = $pc->old_id
                   order by pcomments.datecreated asc
                ");
            //dd(collect($oldDbComments)->count());
            if (!sizeof($oldDbComments)) {
                //$this->comment("No comments found for public consultation with old ID $pc->old_id");
                continue;
            }
            if ($pc->comments_with_trashed_count >= count($oldDbComments)) {
                //$this->comment("All good for public consultation with old ID $pc->old_id");
                continue;
            }

            $text = "Different comments count $pc->comments_with_trashed_count <> ".count($oldDbComments)." for public consultation with old ID $pc->old_id fixed";
            $this->info($text);
            file_put_contents('old_pc_comments_fixed', $text . PHP_EOL, FILE_APPEND);

            Comments::where('object_code', Comments::PC_OBJ_CODE)->where('object_id', $pc->id)->delete();

            $inserted = 0;
            foreach ($oldDbComments as $c) {
                $content = str_replace('&quot;', '"', $c->content);
                $content = str_replace('\n', '<br>', $content);
                Comments::create([
                    'user_id' => $ourUsers[$c->user_id] ?? null,
                    'content' => $content,
                    'object_code' => Comments::PC_OBJ_CODE,
                    'object_id' => $pc->id,
                    'created_at' => $c->created_at,
                    'deleted_at' => $c->deleted_at,
                    'active' => $c->active,
                    'approved' => $c->approved
                ]);
                $inserted++;
            }

            $this->info("$inserted comments inserted for public consultation with old ID $pc->old_id");
        }
        return Command::SUCCESS;
    }
}
