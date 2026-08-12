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

    // Tally Modal State
    public $viewingPollId = null;
    public $pollTallyData = [];
    public $viewingPollTitle = '';

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
        $this->selectedNominees = $poll->nominee_ids ?? [];
    }

    public function saveNominees()
    {
        $poll = IbalongPoll::findOrFail($this->managingPollId);
        $cleanArray = array_map('intval', $this->selectedNominees);

        $poll->update([
            'nominee_ids' => $cleanArray
        ]);

        $this->managingPollId = null;
        $this->selectedNominees = [];

        session()->flash('success', 'Nominee roster successfully locked in for this poll.');
    }

    // --- TALLY BOARD PROTOCOLS ---
    public function openTallyModal($pollId)
    {
        $poll = IbalongPoll::with('votes.team')->findOrFail($pollId);
        $this->viewingPollId = $poll->id;
        $this->viewingPollTitle = $poll->title;

        $voteCounts = $poll->votes->groupBy('team_id');
        $tally = [];

        $nomineeIds = $poll->nominee_ids ?? [];

        // Compile votes for all assigned nominees
        foreach ($nomineeIds as $teamId) {
            $team = Registration::find($teamId) ?? IbalongRegistration::find($teamId);
            $count = isset($voteCounts[$teamId]) ? $voteCounts[$teamId]->count() : 0;

            $tally[] = [
                'team_name' => $team->team_name ?? 'Unknown Team',
                'category' => $team->category ?? 'General',
                'logo' => $team->logo ?? $team->logo_path ?? null,
                'votes' => $count
            ];
        }

        // Account for votes cast outside active nominees just in case
        foreach ($voteCounts as $teamId => $votes) {
            if (!in_array($teamId, $nomineeIds)) {
                $team = IbalongRegistration::find($teamId);
                $tally[] = [
                    'team_name' => $team->team_name ?? 'Unknown Team',
                    'category' => $team->category ?? 'General',
                    'logo' => $team->logo ?? $team->logo_path ?? null,
                    'votes' => $votes->count()
                ];
            }
        }

        // Sort ranking descending by vote count
        usort($tally, function($a, $b) {
            return $b['votes'] <=> $a['votes'];
        });

        $this->pollTallyData = $tally;
    }

    public function closeTallyModal()
    {
        $this->viewingPollId = null;
        $this->pollTallyData = [];
        $this->viewingPollTitle = '';
    }

    public function render()
    {
        $polls = IbalongPoll::with(['votes.team', 'event'])
            ->where('hackathon_id', $this->activeHackathon->id)
            ->latest()
            ->get();

        $events = IbalongEvent::latest()->get();
        $teams = IbalongRegistration::where('status', 'approved')
            ->orderBy('team_name', 'asc')
            ->get();

        return view('livewire.ibalong.admin.poll-manager', compact('polls', 'events', 'teams'))
            ->layout('layouts.dashboard');
    }
}
