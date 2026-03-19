<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evaluation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Str;

class EvaluationList extends Component
{
    use WithPagination;

    public $search = '';
    public $sharingEvaluation = null;
    public $shareSearch = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $evaluation = Evaluation::find($id);
        $evaluation->is_active = !$evaluation->is_active;
        $evaluation->save();

        session()->flash('success', $evaluation->is_active ? 'Form published!' : 'Form unpublished.');
    }

    // --- SECURE DUPLICATE METHOD ---
    public function duplicate($id)
    {
        $original = Evaluation::with('questions')->find($id);

        if (!$original) return;

        $duplicate = $original->replicate();
        $duplicate->title = $original->title . ' (Copy)';
        $duplicate->is_active = false; 

        if (isset($original->slug)) {
            $duplicate->slug = Str::slug($duplicate->title) . '-' . strtolower(Str::random(5));
        }

        $duplicate->save();

        foreach ($original->questions as $question) {
            $newQuestion = $question->replicate();
            $newQuestion->evaluation_id = $duplicate->id; 
            $newQuestion->save();
        }

        session()->flash('success', 'Form and questions duplicated successfully!');
    }

    public function delete($id)
    {
        Evaluation::find($id)->delete();
        session()->flash('success', 'Evaluation form deleted.');
    }

    // --- SECURE SHARE MODAL (Fixes 404 Error) ---
    public function openShareModal($id)
    {
        $evaluation = Evaluation::with(['creator', 'collaborators'])->find($id);

        if (!$evaluation) {
            session()->flash('error', 'Evaluation not found.');
            return;
        }

        if (auth()->user()->role?->role_name !== 'administrator' && $evaluation->created_by !== auth()->id()) {
            session()->flash('error', 'Only the form owner can manage access.');
            return;
        }

        $this->sharingEvaluation = $evaluation;
        $this->shareSearch = '';
    }

    public function closeShareModal()
    {
        $this->sharingEvaluation = null;
    }

    public function addCollaborator($userId)
    {
        if ($this->sharingEvaluation) {
            $this->sharingEvaluation->collaborators()->syncWithoutDetaching([$userId]);
            $this->shareSearch = ''; 
        }
    }

    public function removeCollaborator($userId)
    {
        if ($this->sharingEvaluation) {
            $this->sharingEvaluation->collaborators()->detach($userId);
        }
    }

    #[Computed]
    public function searchResults()
    {
        if (empty($this->shareSearch) || strlen($this->shareSearch) < 2 || !$this->sharingEvaluation) {
            return [];
        }

        return \App\Models\User::where('name', 'like', '%' . $this->shareSearch . '%')
            ->where('id', '!=', auth()->id()) 
            ->where('id', '!=', $this->sharingEvaluation->created_by) 
            ->whereNotIn('id', $this->sharingEvaluation->collaborators->pluck('id')) 
            ->take(5)
            ->get();
    }

    public function render()
    {
        $query = Evaluation::with(['creator', 'collaborators'])->withCount('responses')->latest();

        if (auth()->user()->role?->role_name !== 'administrator') {
            $query->where(function ($q) {
                $q->where('created_by', auth()->id())
                  ->orWhereHas('collaborators', function ($q2) {
                      $q2->where('user_id', auth()->id());
                  });
            });
        }

        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        $evaluations = $query->paginate(10);

        $layoutFile = auth()->user()->role?->role_name === 'administrator'
            ? 'layouts.madya-admin-deck'
            : 'layouts.madya-admin';

        return view('livewire.admin.evaluation-list', compact('evaluations'))
            ->layout($layoutFile);
    }
}