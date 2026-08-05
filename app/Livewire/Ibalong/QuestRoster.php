<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use App\Models\IbalongQuest;

class QuestRoster extends Component
{
    public $isAdminView = false;

    public function mount()
    {
        $role = auth('ibalong')->user()->role_id;
        // Roles 1 (Super), 2 (Admin), 4 (Director), 5 (Facilitator)
        $this->isAdminView = in_array($role, [1, 2, 4, 5]);
    }

    public function togglePublish($id)
    {
        if (!$this->isAdminView) return;
        $quest = IbalongQuest::findOrFail($id);
        $quest->update(['is_published' => !$quest->is_published]);
    }

    public function deleteQuest($id)
    {
        if (!$this->isAdminView) return;

        // This will cascade delete tasks, criteria, submissions, and scores!
        IbalongQuest::findOrFail($id)->delete();
        session()->flash('success', 'Quest and all associated logs have been wiped.');
    }

    public function render()
    {
        // Fetch the team ID if the user is a team member
        $team_id = auth('ibalong')->user()->registration->id ?? null;

        $quests = IbalongQuest::with('submissions')
            ->when(!$this->isAdminView, function($q) use ($team_id) {
                // 1. Must be published
                $q->where('is_published', true)
                  // 2. Security Check: Must be public OR team must have clearance
                  ->where(function ($subQ) use ($team_id) {
                      $subQ->where('is_restricted', false)
                           ->orWhereHas('allowedTeams', function ($accessQ) use ($team_id) {
                               $accessQ->where('team_id', $team_id);
                           });
                  });
            })
            ->orderBy('deadline', 'asc')
            ->get();

        return view('livewire.ibalong.quest-roster', compact('quests'))->layout('layouts.dashboard');
    }
}
