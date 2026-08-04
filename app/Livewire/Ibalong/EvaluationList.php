<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use App\Models\IbalongEvaluation;

class EvaluationList extends Component
{
    public $isAdminView = false;
    public $hasTeam = false;

    public function mount()
    {
        $user = auth('ibalong')->user();
        $role = $user->role_id ?? 0;

        $this->isAdminView = in_array($role, [1, 2, 4]);

        // Check if the current user has an active team registration attached to them
        $this->hasTeam = $user->registration ? true : false;
    }

    public function toggleStatus($id)
    {
        if (!$this->isAdminView) return;
        $evaluation = IbalongEvaluation::findOrFail($id);
        $evaluation->update(['is_active' => !$evaluation->is_active]);
    }

    public function deleteEvaluation($id)
    {
        if (!$this->isAdminView) return;
        IbalongEvaluation::findOrFail($id)->delete();
        session()->flash('success', 'Form blueprint and all associated responses permanently purged.');
    }

    public function render()
    {
        $evaluations = IbalongEvaluation::when(!$this->isAdminView, function($query) {
            $query->where('is_active', true);

            // If they are not in a team, ONLY show them public evaluations
            if (!$this->hasTeam) {
                $query->where('access_level', 'public');
            }
        })->withCount('responses')->orderBy('created_at', 'desc')->get();

        return view('livewire.ibalong.evaluation-list', compact('evaluations'))->layout('layouts.dashboard');
    }
}
