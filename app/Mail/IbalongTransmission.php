<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class IbalongTransmission extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject;
    public $messageBody;
    public $teamName;

    public function __construct($subject, $messageBody, $teamName = 'Cohort')
    {
        $this->subject = $subject;
        $this->messageBody = $messageBody;
        $this->teamName = $teamName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[COMMUNITY CENTER] ' . $this->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ibalong.transmission',
        );
    }
}
