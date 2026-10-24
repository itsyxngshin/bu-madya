<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventBlastMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $customSubject;
    public $customMessage;
    public $organizerName;

    public function __construct(Event $event, $subject, $message, $organizerName)
    {
        $this->event = $event;
        $this->customSubject = $subject;
        $this->customMessage = $message;
        $this->organizerName = $organizerName;
    }

    public function build()
    {
        return $this->subject($this->customSubject)
                    ->markdown('emails.event-blast'); // We will use standard markdown for simple announcements
    }
}
