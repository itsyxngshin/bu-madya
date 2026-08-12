<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongHackathon;
use App\Models\IbalongPoll;
use App\Models\IbalongEvent;
use App\Models\IbalongRegistration;

class PollManager extends Component
{
    public $activeHackathon;

    // Form State
    public $title = '';
    public $requireTicket = false;
    public $selectedEventId = '';

    // Nominee Management State
    public $managingPollId = null;
    public $selectedNominees = [];

    public function mount()
    {
        $role = auth('ibalong')->user()->role_id ?? 0;
        if (!in_array($role, [1, 2, 4])) { abort(403); }

        $this->activeHackathon = IbalongHackathon::firstOrCreate(
            ['status' => 'active'],
            ['name' => 'Heroes of Innovation 2026']
        );
    }

    public function createPoll()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'requireTicket' => 'boolean',
            'selectedEventId' => 'required_if:requireTicket,true'
        ]);

        // Direct creation to bypass relationship requirements
        IbalongPoll::create([
            'hackathon_id' => $this->activeHackathon->id,
            'event_id' => $this->requireTicket ? $this->selectedEventId : null,
            'title' => $this->title,
            'is_active' => false,
            'require_ticket' => $this->requireTicket
        ]);

        $this->reset(['title', 'selectedEventId', 'requireTicket']);
        session()->flash('success', 'Voting Poll successfully initialized and linked.');
    }

    public function togglePollStatus($id)
    {
        $poll = IbalongPoll::findOrFail($id);

        // Optional: If you only want one active poll at a time, uncomment the line below:
        // IbalongPoll::where('hackathon_id', $this->activeHackathon->id)->update(['is_active' => false]);

        $poll->update(['is_active' => !$poll->is_active]);
        session()->flash('success', 'Poll broadcast status updated.');
    }

    public function deletePoll($id)
    {
        IbalongPoll::findOrFail($id)->delete();
        session()->flash('success', 'Poll successfully purged.');
    }

    // --- NOMINEE PROTOCOLS ---
    public function openNomineeManager($pollId)
    {
        $poll = IbalongPoll::findOrFail($pollId);
        $this->managingPollId = $poll->id;

        // Load existing nominees, defaulting to an empty array
        $this->selectedNominees = $poll->nominee_ids ?? [];
    }

    public function saveNominees()
    {
        $poll = IbalongPoll::findOrFail($this->managingPollId);

        // Convert the string checkbox values into pure integers before saving
        $cleanArray = array_map('intval', $this->selectedNominees);

        $poll->update([
            'nominee_ids' => $cleanArray
        ]);

        $this->managingPollId = null;
        $this->selectedNominees = [];

        session()->flash('success', 'Nominee roster successfully locked in for this poll.');
    }

    public function render()
    {
        $polls = clone IbalongPoll::with(['votes.team', 'event'])
            ->where('hackathon_id', $this->activeHackathon->id)
            ->latest()
            ->get();

        // Using latest() to prevent 500 SQL errors
        $events = IbalongEvent::latest()->get();

        // Fetch all approved teams to populate the nominee checklist
        $teams = IbalongRegistration::where('status', 'approved')
            ->orderBy('team_name', 'asc')
            ->get();

        return view('livewire.ibalong.admin.poll-manager', compact('polls', 'events', 'teams'))
            ->layout('layouts.dashboard');
    }
}
