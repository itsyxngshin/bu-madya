<?php

namespace App\Mail;

use App\Models\LinkageProposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProposalReceivedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $proposal;

    public function __construct(LinkageProposal $proposal)
    {
        $this->proposal = $proposal;
    }

    public function build()
    {
        return $this->subject('Proposal Received: ' . $this->proposal->title)
                    ->view('emails.proposal-received');
    }
}