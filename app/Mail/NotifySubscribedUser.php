<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifySubscribedUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The subscribed model instance
     */
    public $data;

    /**
     * The user model instance
     *
     * @var User
     */
    private User $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $data)
    {
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $modelInstance = $this->data['modelInstance'];
        $markdown = $this->data['markdown'];
        $text = $this->data['text'];
        $user = $this->user;
        $from = (env('MAIL_FROM_ADDRESS')) ? env('MAIL_FROM_ADDRESS') : "info@strategy.bg";
        $class_expl = explode("\\", get_class($modelInstance));
        $model_name = end($class_expl);
        $controller = $model_name."Controller@show";
        $action = "\App\Http\Controllers\\$controller";
        $url = action($action, $modelInstance->id);

        return $this->from($from)
            ->subject(__('Notification'))
            ->markdown("emails.subscriptions.$markdown", compact('user','modelInstance', 'url', 'text'));
    }
}
