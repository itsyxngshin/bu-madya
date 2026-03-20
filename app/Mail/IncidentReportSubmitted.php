<?php

namespace App\Mail;

use App\Models\IncidentReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class IncidentReportSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $report;

    /**
     * Create a new message instance.
     */
    public function __construct(IncidentReport $report)
    {
        $this->report = $report;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confidential: Incident Report Received (' . $this->report->case_number . ')',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welfare.incident-report',
        );
    }
}