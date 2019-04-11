<?php

namespace App\Mail;

use App\UserCode;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Registration extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $userCode;
    public $tries = 3;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(UserCode $userCode)
    {
        $this->userCode = $userCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.auth.registration');
    }
}
