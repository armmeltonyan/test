<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendUserMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected User $user,
    ) {}

    /**
     * Build mail
     *
     * @return SendUserMail
     */
    public function build(): SendUserMail
    {
        return $this->subject(__(''))
            ->view(
                'emails.send-user-notification',
                [
                    'user' => $this->user,
                ],
            );
    }
}
