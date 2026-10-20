<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;
use App\Models\EventRegistration;

class EventTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $registrationRecord;
    public $qrCodeUrl;

    public function __construct(Event $event, EventRegistration $registrationRecord)
    {
        $this->event = $event;
        $this->registrationRecord = $registrationRecord;
        
        // Generate a reliable public QR code URL using a standard API for email compatibility
        $this->qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($registrationRecord->ticket_code);
    }

    public function build()
    {
        return $this->subject('[REGISTRATION TICKET] ' . $this->event->title)
                    ->view('emails.event-ticket');
    }
}