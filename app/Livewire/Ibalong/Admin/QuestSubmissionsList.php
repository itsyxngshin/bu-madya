<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongQuest;
use App\Models\IbalongQuestSubmission;

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

        // Eager load submissions with their respective team and score data
        $this->submissions = IbalongQuestSubmission::with(['team', 'scores.judge'])
            ->where('quest_id', $quest_id)
            ->orderByRaw("FIELD(status, 'submitted', 'reviewing', 'reviewed', 'draft')")
            ->orderBy('submitted_at', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.ibalong.admin.quest-submissions-list')->layout('layouts.dashboard');
    }
}
