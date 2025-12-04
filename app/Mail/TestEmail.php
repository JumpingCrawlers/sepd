<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @param array $data
     */
    public function __construct()
    {
    }

    /**
     * Build the message.
     *
     * @return Mailable $this
     */
    public function build()
    {
        return $this->subject("Test email")->markdown('emails.test');
    }
}
