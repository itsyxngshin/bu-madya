<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $registrationRecord; // Match whatever variable your blade view uses!

        public function __construct(Event $event, EventRegistration $registrationRecord)
        {
            $this->event = $event;
            $this->registrationRecord = $registrationRecord;
        }

        public function build()
        {
            return $this->subject('[REGISTRATION TICKET] ' . $this->event->title)
                        ->view('emails.event-ticket');
        }
}
