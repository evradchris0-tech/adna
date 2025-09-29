<?php

namespace App\Notifications;

use App\Models\JWTModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerification extends Notification
{
    use Queueable;

    public $mailtype = 'verifie';

    /**
     * Create a new notification instance.
     */
    public function __construct(string $type = 'verifie')
    {
        $this->mailtype = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $payload = array('email' => $notifiable->email, 'exp' => (time() + 7200));
        $token = JWTModel::generate_jwt($payload, env('JWT_SECRET_KEY'));
        if ($this->mailtype == 'verifie') {
            $url = url('/') . '/' . $notifiable->firstname . '/verify/email/' . $token . '/' . $notifiable->id;
            $btnText = 'valider mon compte';
            $view = 'notifications::email';
            $gretting = "Valider l'email de votre compte";
            $message = 'Vous avez récemment sélectionné ' . $notifiable->email . ' comme votre identifiant ' . env('APP_NAME') . '. Veuillez confirmer que cette adresse e‑mail vous appartient en cliquant sur le boutton ci‑dessous.';
        } else {
            $url = url('/') . '/reinitialise/' . $token;
            $btnText = 'reiitialiser mon compte';
            $view = 'notifications::reset';
            $gretting = 'Renitialiser mon mot de passe';
            $message = 'vous avez fais une demande de reinitialisation de mot de passe cliquer sur le boutton ci-apres pour reinitialiser vos informations. ci vous n\'etes pas l\'auteur de cette demande rien a craindre rien ne seras fais sans votre concentement. ';
        }
        return (new MailMessage)
            ->view($view)
            ->greeting($gretting)
            ->salutation($message)
            ->action($btnText, $url)
            ->line("Merci " . env('APP_NAME'));
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
