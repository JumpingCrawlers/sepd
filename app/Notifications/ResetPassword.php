<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->markdown('notifications.reset-password')
            ->subject('Restablecer contraseña')
            ->greeting('Estimado usuario:')
            ->line('Para restablecer su contraseña pulse el siguiente botón, se le redirigirá a una página donde podrá establecer una nueva contraseña.')
            ->action('Establece tu nueva contraseña', url(config('app.url').route('password.reset', $this->token, false)))
            ->salutation('Un saludo,<br>Secretaría SEPD');
    }

}
