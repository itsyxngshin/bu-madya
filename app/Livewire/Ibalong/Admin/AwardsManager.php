<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongHackathon;
use App\Models\IbalongAward;
use App\Models\IbalongRegistration;

class AwardsManager extends Component
{
    public $activeHackathon;

    // Form State
    public $title = '';
    public $type = 'ranking'; // ranking or special
    public $remarks = '';

    // Assignment State
    public $assigningAwardId = null;
    public $selectedTeamId = '';

    public function mount()
    {
        // Enforce Command Center Clearance
        $role = auth('ibalong')->user()->role_id ?? 0;
        if (!in_array($role, [1, 2, 4])) { abort(403); }

        $this->activeHackathon = IbalongHackathon::firstOrCreate(
            ['status' => 'active'],
            ['name' => 'Heroes of Innovation 2026']
        );
    }

    public function createAward()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:ranking,special',
        ]);

        $this->activeHackathon->awards()->create([
            'title' => $this->title,
            'type' => $this->type,
            'remarks' => $this->remarks,
            'is_published' => false
        ]);

        $this->reset(['title', 'type', 'remarks']);
        session()->flash('success', 'Award established in the matrix.');
    }

    public function togglePublish($awardId)
    {
        $award = IbalongAward::findOrFail($awardId);
        $award->update(['is_published' => !$award->is_published]);
    }

    public function deleteAward($awardId)
    {
        IbalongAward::findOrFail($awardId)->delete();
        session()->flash('success', 'Award has been purged from the system.');
    }

    // --- COHORT ASSIGNMENT PROTOCOLS ---
    public function openAssignModal($awardId)
    {
        $award = IbalongAward::findOrFail($awardId);
        $this->assigningAwardId = $award->id;
        $this->selectedTeamId = $award->team_id ?? '';
    }

    public function assignTeamToAward()
    {
        $award = IbalongAward::findOrFail($this->assigningAwardId);

        $award->update([
            'team_id' => $this->selectedTeamId ?: null
        ]);

        $this->assigningAwardId = null;
        $this->selectedTeamId = '';
        session()->flash('success', 'Cohort successfully assigned to the award!');
    }

    public function removeWinner($awardId)
    {
        IbalongAward::findOrFail($awardId)->update(['team_id' => null]);
        session()->flash('success', 'Winner designation cleared.');
    }

    public function render()
    {
        $awards = IbalongAward::with('team')
            ->where('hackathon_id', $this->activeHackathon->id)
            ->orderBy('type', 'asc') // Groups Rankings together, then Specials
            ->get();

        $teams = IbalongRegistration::where('status', 'approved')
            ->orderBy('team_name', 'asc')
            ->get();

        return view('livewire.ibalong.admin.awards-manager', compact('awards', 'teams'))
            ->layout('layouts.dashboard');
    }
}
