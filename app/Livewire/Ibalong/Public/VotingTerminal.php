<?php

namespace App\Livewire\Ibalong\Public;

use Livewire\Component;
use App\Models\IbalongPoll;
use App\Models\IbalongRegistration;
use App\Models\IbalongVote;

class VotingTerminal extends Component
{
    public $activePoll;
    public $teams = [];

    // Voting State
    public $selectedTeamId = null;
    public $ticketCode = '';
    public $hasVoted = false;

    public function mount()
    {
        $this->activePoll = IbalongPoll::where('is_active', true)->first();

        // Strict Nominee Enforcement
        if ($this->activePoll && !empty($this->activePoll->nominee_ids)) {
            $this->teams = IbalongRegistration::whereIn('id', $this->activePoll->nominee_ids)
                ->where('status', 'approved')
                ->orderBy('team_name', 'asc')
                ->get();
        } else {
            $this->teams = collect();
        }

        if ($this->activePoll && session()->has('voted_poll_' . $this->activePoll->id)) {
            $this->hasVoted = true;
        }
    }

    public function selectTeam($teamId)
    {
        $this->selectedTeamId = $teamId;
    }

    public function cancelSelection()
    {
        $this->selectedTeamId = null;
        $this->ticketCode = '';

        // Dispatch an event to tell JavaScript to shut down the camera if it's running
        $this->dispatch('close-scanner');
    }

    public function castVote()
    {
        if (!$this->activePoll || !$this->activePoll->is_active) {
            session()->flash('error', 'SYSTEM REJECT: The voting broadcast has been terminated.');
            return;
        }

        if (!$this->selectedTeamId) {
            session()->flash('error', 'SYSTEM REJECT: You must select a cohort to cast your vote.');
            return;
        }

        // --- Ticket Validation Protocol ---
        if ($this->activePoll->require_ticket) {
            $this->validate([
                'ticketCode' => 'required|string',
            ], [
                'ticketCode.required' => 'An official event ticket code is required to authorize this vote.'
            ]);

            $ticketUsed = IbalongVote::where('poll_id', $this->activePoll->id)
                ->where('ticket_code', $this->ticketCode)
                ->exists();

            if ($ticketUsed) {
                session()->flash('error', 'SYSTEM REJECT: This ticket code has already been used to cast a vote.');
                return;
            }
        }

        // --- Record the Vote ---
        IbalongVote::create([
            'poll_id' => $this->activePoll->id,
            'team_id' => $this->selectedTeamId,
            'ticket_code' => $this->activePoll->require_ticket ? $this->ticketCode : null,
            'ip_address' => request()->ip(),
        ]);

        // Lock terminal
        session()->put('voted_poll_' . $this->activePoll->id, true);
        $this->hasVoted = true;

        $this->cancelSelection();

        session()->flash('success', 'Authorization Complete. Your vote has been officially recorded in the logs.');
    }

    public function render()
    {
        return view('livewire.ibalong.public.voting-terminal')
            ->layout('layouts.guest');
    }
}
