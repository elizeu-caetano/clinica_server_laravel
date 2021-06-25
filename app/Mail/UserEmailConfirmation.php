<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserEmailConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->user->link = 'https://2eclinica.com/confirmacao-email/' . $this->user->uuid . '/' . $this->user->token;

        $this->subject('Novo Usuário');
        $this->to($this->user->email, $this->user->name);
        $this->from('elizeucaetano@outlook.com', $this->user->contractor->fantasy_name);

        return $this->markdown('mail.emailconfirmation', [
            'user' => $this->user
        ]);
    }
}
