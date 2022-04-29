<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailPost extends Mailable
{

    use Queueable, SerializesModels;
    protected $activationLink;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($activationLink)
    {
        $this->activationLink = $activationLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.email_view', ['activationLink' => $this->activationLink])
            ->subject('Web-liter.ru');
    }
}
