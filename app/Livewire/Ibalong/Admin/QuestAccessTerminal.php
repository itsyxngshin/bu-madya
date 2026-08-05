<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongQuest;
use App\Models\IbalongRegistration;

class QuestAccessTerminal extends Component
{
    public IbalongQuest $quest;
    public $teams = [];
    public $clearedTeamIds = [];

    public function mount($quest_id)
    {
        // Strict RBAC: Admins (1, 2) and Facilitators (4)
        $role = auth('ibalong')->user()->role_id ?? 0;
        if (!in_array($role, [1, 2, 4])) {
            abort(403, 'ACCESS DENIED: Clearance Terminal is restricted to Command Operations.');
        }

        $this->quest = IbalongQuest::findOrFail($quest_id);

        // Load all active cohorts
        $this->teams = IbalongRegistration::orderBy('team_name', 'asc')->get();

        // Load currently cleared cohorts
        $this->clearedTeamIds = $this->quest->allowedTeams()->pluck('ibalong_registrations.id')->toArray();
    }

    public function toggleRestriction()
    {
        $this->quest->is_restricted = !$this->quest->is_restricted;
        $this->quest->save();

        $state = $this->quest->is_restricted ? 'LOCKED (Restricted)' : 'UNLOCKED (Public)';
        session()->flash('success', "Security Protocol Updated: Quest is now {$state}.");
    }

    public function toggleClearance($team_id)
    {
        if (in_array($team_id, $this->clearedTeamIds)) {
            // Revoke clearance
            $this->quest->allowedTeams()->detach($team_id);
            $this->clearedTeamIds = array_diff($this->clearedTeamIds, [$team_id]);
        } else {
            // Grant clearance
            $this->quest->allowedTeams()->attach($team_id);
            $this->clearedTeamIds[] = $team_id;
        }
    }

    public function render()
    {
        return view('livewire.ibalong.admin.quest-access-terminal')->layout('layouts.dashboard');
    }
}
