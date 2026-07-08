<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\IbalongCommitteeMember;

class VolunteerApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $member;

    public function __construct(IbalongCommitteeMember $member)
    {
        $this->member = $member;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[HOI 2026] MISSION UPDATE: You have been selected! ',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.volunteer-approved',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}