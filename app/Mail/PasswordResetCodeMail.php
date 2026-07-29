<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordResetCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($code, $user)
    {
        $this->code = $code;
        $this->email = $user->email; // Store the user's email for use in the email view
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Password Reset Code')
                    ->view('emails.password_reset_code')
                    ->with([
                        'code' => $this->code,
                        'email' => $this->email,
                    ]);
    }
}