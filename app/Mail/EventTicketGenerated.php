<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\IbalongEvent;
use App\Models\IbalongEventRegistration;

class EventTicketGenerated extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $registration;

    public function __construct(IbalongEvent $event, IbalongEventRegistration $registration)
    {
        $this->event = $event;
        $this->registration = $registration;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎟️ Your Boarding Pass: ' . $this->event->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ibalong.event-ticket',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
