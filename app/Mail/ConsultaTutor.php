<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use App\User;

class ConsultaTutor extends Mailable
{
    use Queueable, SerializesModels;

    public $senderId;
    public $receiverId;
    public $courseTitle;
    public $message;

    /**
     * Consulta Tutor mail template constructor
     *
     * @param int $sender_id
     * @param int $receiver_id
     * @param string $course_title
     * @param string $message
     */
    public function __construct($sender_id, $receiver_id, $course_title, $message)
    {
        $this->senderId = $sender_id;
        $this->receiverId = $receiver_id;
        $this->courseTitle = $course_title;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $sender = User::find($this->senderId);
        $receiver = User::find($this->receiverId);

        return $this->subject("Mensaje con tutor/a")->markdown('emails.admin.consulta-tutor', [
            'sender' => $sender,
            'receiver' => $receiver,
            'title' => $this->courseTitle,
            'message' => $this->message
        ]);
    }
}
