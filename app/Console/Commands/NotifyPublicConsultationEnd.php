<?php

namespace App\Console\Commands;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Mail\PublicConsultationEnd;
use App\Models\Consultations\PublicConsultation;
use App\Models\UserSubscribe;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyPublicConsultationEnd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:pc_end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to moderator for ended public consultaion two days after ended. We give time (1 day) for generating documents';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $endedBefore = Carbon::now()->subDays(1)->format('Y-m-d');
        $pcs = PublicConsultation::with(['author'])->Ended()
            ->where('open_to', '<', $endedBefore)
            ->whereHas('author')
            ->whereNull('end_notify')
            ->limit(10)
            ->get();
        if($pcs->count()){
            foreach ($pcs as $pc) {
                $to = config('app.env') != 'production' ? config('mail.local_to_mail') : $pc->author->email;
                Mail::to($to)->send(new PublicConsultationEnd($pc));
                $pc->end_notify = $now;
                $pc->save();
            }
        }
    }
}
