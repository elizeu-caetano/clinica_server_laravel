<?php

namespace App\Mail;

use App\Http\Resources\Acl\ContractorResource;
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

        $contractor = new ContractorResource($this->user->contractor);

        $this->subject('Novo Usuário');
        $this->to($this->user->email, $this->user->name);
        $this->from('elizeucaetano@outlook.com', $contractor->fantasy_name);

        return $this->markdown('mail.emailconfirmation', [
            'user' => $this->user,
            'contractor' => $contractor
        ]);
    }
}
