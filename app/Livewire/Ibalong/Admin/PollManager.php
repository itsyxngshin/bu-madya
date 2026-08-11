<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongHackathon;
use App\Models\IbalongPoll;
use App\Models\IbalongEvent; // Imported Event model
use Illuminate\Support\Facades\DB;

class PollManager extends Component
{
    public $activeHackathon;
    public $title = "People's Choice Award";
    public $requireTicket = true;
    public $selectedEventId = ''; // Holds the linked event

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
            'selectedEventId' => 'required_if:requireTicket,true' // Must pick an event if ticket is required
        ]);

        $this->activeHackathon->polls()->create([
            'event_id' => $this->requireTicket ? $this->selectedEventId : null,
            'title' => $this->title,
            'is_active' => false,
            'require_ticket' => $this->requireTicket
        ]);

        $this->reset(['title', 'selectedEventId']);
        $this->requireTicket = true;
        session()->flash('success', 'Voting Poll successfully initialized and linked.');
    }

    public function togglePollStatus($pollId)
    {
        $poll = IbalongPoll::findOrFail($pollId);
        $poll->update(['is_active' => !$poll->is_active]);
        session()->flash('success', 'Poll broadcasting status updated.');
    }

    public function toggleTicketRequirement($pollId)
    {
        $poll = IbalongPoll::findOrFail($pollId);
        $poll->update(['require_ticket' => !$poll->require_ticket]);
        session()->flash('success', 'Poll security requirement updated.');
    }

    public function deletePoll($pollId)
    {
        IbalongPoll::findOrFail($pollId)->delete();
        session()->flash('success', 'Poll and all associated votes purged.');
    }

    public function render()
    {
        // Added 'event' eager loading
        $polls = IbalongPoll::with(['votes.team', 'event'])
            ->where('hackathon_id', $this->activeHackathon->id)
            ->latest()
            ->get();

        // Fetch events to link to the poll
        $events = IbalongEvent::orderBy('start_date', 'asc')->get();

        return view('livewire.ibalong.admin.poll-manager', compact('polls', 'events'))
            ->layout('layouts.dashboard');
    }
}
