<?php

namespace App\Mail;

use App\Models\MembershipApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $application; // Public property makes it accessible in the view

    public function __construct(MembershipApplication $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        return $this->subject('Welcome to BU MADYA!') 
                    ->markdown('emails.application-approved');
    }

}