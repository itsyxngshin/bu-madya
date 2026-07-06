<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\IbalongRegistration;

class IbalongRegistrationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;
    public $teamLeader;

    public function __construct(IbalongRegistration $registration, $teamLeader)
    {
        $this->registration = $registration;
        $this->teamLeader = $teamLeader;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Received - Heroes of Innovation Challenge 2026',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ibalong.registration-received',
        );
    }
}