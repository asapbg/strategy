<?php

namespace App\Console\Commands;

use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
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
        file_put_contents('old_pc_comments', '');
        $pcWithoutComment = PublicConsultation::select('id','old_id')
            ->withCount('comments')
            ->whereNotNull('old_id')
            ->withTrashed()
            //->whereDoesntHave('comments')
            //->whereIn('id', [11065])
            ->get();

        if ($pcWithoutComment->count() == 0) {
            $this->comment("There are no public consultation without comments");
            return Command::SUCCESS;
        }
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
            if (!sizeof($oldDbComments)) {
                $this->comment("No comments found for public consultation with old ID $pc->old_id");
                continue;
            }
            if ($pc->comments_count != count($oldDbComments)) {
                $text = "Different comments count $pc->comments_count <> ".count($oldDbComments)." for public consultation with old ID $pc->old_id";
                $this->info($text);
                file_put_contents('old_pc_comments', $text . PHP_EOL, FILE_APPEND);
            } else {
                $this->comment("All good for public consultation with old ID $pc->old_id");
            }
            continue;
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
