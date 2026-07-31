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
        $this->isAdminView = in_array($role, [1, 2, 4, 5]);
    }

    public function togglePublish($id)
    {
        if (!$this->isAdminView) return;
        $quest = IbalongQuest::findOrFail($id);
        $quest->update(['is_published' => !$quest->is_published]);
    }

    public function render()
    {
        // Admins see everything. Teams only see published quests.
        $quests = IbalongQuest::with('submissions')
            ->when(!$this->isAdminView, function($q) {
                $q->where('is_published', true);
            })
            ->orderBy('deadline', 'asc')
            ->get();

        return view('livewire.ibalong.quest-roster', compact('quests'))->layout('layouts.dashboard');
    }
}