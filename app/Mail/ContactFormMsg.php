<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMsg extends Mailable
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

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from = $this->data['email'];
        $name = $this->data['name'] ?? config('mail.from.name');
        $message = $this->data['message'];
        return $this->from($from, $name)
            ->subject('Портал за Обществени консултации: '.$this->data['subject'] )
            ->markdown("emails.contact_form", compact('message'));
    }
}
