<?php

namespace App\Livewire\Ibalong\Public;

use Livewire\Component;
use App\Models\IbalongPoll;
use App\Models\IbalongRegistration;
use App\Models\IbalongVote;
use App\Models\IbalongEventRegistration; // <-- Integrating your Event Module

class VotingTerminal extends Component
{
    public $activePoll;

    // Modal State
    public $selectedTeam = null;
    public $ticketCode = '';

    public function mount()
    {
        // Find the first currently active poll
        $this->activePoll = IbalongPoll::where('is_active', true)->first();
    }

    public function openVoteModal($teamId)
    {
        if (!$this->activePoll) return;

        $this->selectedTeam = IbalongRegistration::find($teamId);
        $this->ticketCode = '';
    }

    public function castVote()
    {
        if (!$this->activePoll || !$this->selectedTeam) return;

        // Security Check 1: Ticket Requirements
        if ($this->activePoll->require_ticket) {
            $this->validate([
                'ticketCode' => 'required|string'
            ], [
                'ticketCode.required' => 'An Event Ticket Code is required to cast a vote.'
            ]);

            // Validate against the exact IbalongEventRegistration model
            $registration = IbalongEventRegistration::where('ticket_code', $this->ticketCode)->first();

            if (!$registration) {
                session()->flash('error', 'SYSTEM REJECT: Invalid or unrecognized ticket code.');
                return;
            }

            /*
            // 💡 OPTIONAL PRO-TIP: STRICT ATTENDANCE LOCK
            // If you only want people who ACTUALLY ATTENDED (scanned their QR at the door) to vote,
            // you can check your IbalongEventAttendance table here by uncommenting this block:

            $hasAttended = \App\Models\IbalongEventAttendance::where('registration_id', $registration->id)->exists();
            if (!$hasAttended) {
                session()->flash('error', 'SYSTEM REJECT: Ticket is valid, but attendance was not verified at the venue.');
                return;
            }
            */

            // Security Check 2: Has this ticket already voted in THIS poll?
            $alreadyVoted = IbalongVote::where('poll_id', $this->activePoll->id)
                ->where('ticket_code', $this->ticketCode)
                ->exists();

            if ($alreadyVoted) {
                session()->flash('error', 'SYSTEM REJECT: This ticket code has already cast a vote.');
                return;
            }
        }

        // Execute Vote
        IbalongVote::create([
            'poll_id' => $this->activePoll->id,
            'team_id' => $this->selectedTeam->id,
            'ticket_code' => $this->activePoll->require_ticket ? $this->ticketCode : null,
            'ip_address' => request()->ip()
        ]);

        $this->selectedTeam = null;
        session()->flash('success', 'VOTE SECURED! Thank you for participating in the People\'s Choice Award.');
    }

    public function render()
    {
        // Load all approved teams to display as candidates
        $teams = IbalongRegistration::where('status', 'approved')
            ->orderBy('team_name', 'asc')
            ->get();

        // Using a generic public layout since attendees shouldn't need to log in to the portal
        return view('livewire.ibalong.public.voting-terminal', compact('teams'))
            ->layout('layouts.guest');
    }
}
