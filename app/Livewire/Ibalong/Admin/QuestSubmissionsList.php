<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongQuest;
use App\Models\IbalongQuestSubmission;
use App\Models\IbalongRegistration;

class QuestSubmissionsList extends Component
{
    public $quest;
    public $submissions;

    public function mount($quest_id)
    {
        // Ensure only authorized roles can access this list
        $role = auth('ibalong')->user()->role_id ?? 0;
        if (!in_array($role, [1, 2, 4, 5])) {
            abort(403, 'ACCESS DENIED: Only the Council may view submission manifests.');
        }

        // Load the quest and its max possible score (sum of all criteria)
        $this->quest = IbalongQuest::with('criteria')->findOrFail($quest_id);

        $this->loadSubmissions();
    }

    public function loadSubmissions()
    {
        // Eager load submissions with their respective team and score data
        $this->submissions = IbalongQuestSubmission::with(['team', 'scores.judge'])
            ->where('quest_id', $this->quest->id)
            ->orderByRaw("FIELD(status, 'submitted', 'reviewing', 'reviewed', 'draft')")
            ->orderBy('submitted_at', 'asc')
            ->get();
    }

    public function updateCategory($team_id, $new_category)
    {
        // Strict RBAC: Only Admins (1, 2) and Directors (4) can reassign categories. Judges (5) cannot.
        $role = auth('ibalong')->user()->role_id ?? 0;
        if (!in_array($role, [1, 2, 4])) {
            abort(403, 'ACCESS DENIED: You lack clearance to modify cohort divisions.');
        }

        $team = IbalongRegistration::findOrFail($team_id);
        $team->update([
            'category' => $new_category
        ]);

        // Refresh the submissions data to reflect changes immediately
        $this->loadSubmissions();

        session()->flash('success', "Cohort '{$team->team_name}' reassigned to {$new_category}.");
    }

    public function render()
    {
        return view('livewire.ibalong.admin.quest-submissions-list')->layout('layouts.dashboard');
    }
}
