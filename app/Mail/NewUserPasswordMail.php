<?php

namespace App\Mail;

use App\Http\Resources\Acl\ContractorResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class NewUserPasswordMail extends Mailable
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
        $contractor = new ContractorResource($this->user->contractor);

        $this->subject('Novo Usuário');
        $this->to($this->user->email, Str::title($this->user->name));
        $this->from($contractor->email, Str::title($contractor->fantasy_name));

        return $this->markdown('mail.new-user-password', [
            'user' => $this->user,
            'contractor' => $contractor
        ]);
    }
}
