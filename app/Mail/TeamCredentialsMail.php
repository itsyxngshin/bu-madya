<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $teamName;
    public $leaderName;
    public $email;
    public $password;

    public function __construct($teamName, $leaderName, $email, $password)
    {
        $this->teamName = $teamName;
        $this->leaderName = $leaderName;
        $this->email = $email;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('ACCESS GRANTED: Your HOI 2026 Community Center Credentials')
                    ->view('emails.ibalong.team-credentials');
    }
}
