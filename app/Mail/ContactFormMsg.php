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
        $from = (config('mail.from.address')) ? config('mail.from.address') : "info@strategy.bg";
        $message = $this->data['message'];
        return $this->from($from)
            ->subject('Портал за Обществени консултации: '.$this->data['subject'] )
            ->markdown("emails.contact_form", compact('message'));
    }
}
