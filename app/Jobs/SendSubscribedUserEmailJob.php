<?php

namespace App\Jobs;

use App\Mail\NotifySubscribedUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSubscribedUserEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var mixed
     */
    private mixed $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $adminUsers = $this->data['adminUsers'];
        $subscribedUsers = $this->data['subscribedUsers'];
        unset($this->data['subscribedUsers']);

        if ($adminUsers) {
            foreach ($adminUsers as $adminUser) {
                $this->data['text'] = __('New public consultation was created');
                Mail::to($adminUser['email'])->send(new NotifySubscribedUser($adminUser, $this->data));
            }
        }
        foreach ($subscribedUsers as $subscribedUser) {
            $user = $subscribedUser->user;
            $this->data['text'] = __('New public consultation was created');
            Mail::to($user['email'])->send(new NotifySubscribedUser($user, $this->data));
        }
    }
}
