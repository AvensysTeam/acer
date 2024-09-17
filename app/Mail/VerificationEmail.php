<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $userid;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userid)
    {
        $this->userid = $userid;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {        

        $user = User::findOrFail($this->userid);
        if ($user->hasVerifiedEmail()) {
            return 'User email already verified.';
        }

        // Generate the verification link
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify', // Named route for email verification
            Carbon::now()->addMinutes(1440), // Expiration time (e.g., 60 minutes)
            ['id' => $user->id, 'hash' => sha1($user->email)] // Route parameters
        );

        return $this->view('emails.verification')->with('verificationUrl',$verificationUrl);
    }
}
