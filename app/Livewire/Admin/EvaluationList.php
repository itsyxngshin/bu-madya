<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evaluation;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str; // <-- Add this import

#[Layout('layouts.madya-admin-deck')]
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

    // --- NEW DUPLICATE METHOD ---
    public function duplicate($id)
    {
        // 1. Find the original and eager-load its questions to optimize database queries
        $original = Evaluation::with('questions')->find($id);

        if (!$original) return;

        // 2. Clone the main evaluation record
        $duplicate = $original->replicate();
        $duplicate->title = $original->title . ' (Copy)';
        $duplicate->is_active = false; // Force copies to be Drafts by default

        // Prevent Unique Constraint errors if you use slugs
        if (isset($original->slug)) {
            $duplicate->slug = Str::slug($duplicate->title) . '-' . strtolower(Str::random(5));
        }

        $duplicate->save();

        // 3. Clone all associated questions
        foreach ($original->questions as $question) {
            $newQuestion = $question->replicate();
            $newQuestion->evaluation_id = $duplicate->id; // Attach to the new form
            $newQuestion->save();

            foreach ($question->options as $option) {
               $newOption = $option->replicate();
               $newOption->question_id = $newQuestion->id; // Attach to the new question
               $newOption->save();
            }
        }

        session()->flash('success', 'Form and questions duplicated successfully!');
    }

    public function delete($id)
    {
        Evaluation::find($id)->delete();
        session()->flash('success', 'Evaluation form deleted.');
    }

    public function openShareModal(Evaluation $evaluation)
    {
        // Only Admins and the original Creator can share the form
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
            $this->shareSearch = ''; // Clear search after adding
        }
    }

    public function removeCollaborator($userId)
    {
        if ($this->sharingEvaluation) {
            $this->sharingEvaluation->collaborators()->detach($userId);
        }
    }

    // A computed property to fetch users based on the search input
    #[Livewire\Attributes\Computed]
    public function searchResults()
    {
        if (empty($this->shareSearch) || strlen($this->shareSearch) < 2 || !$this->sharingEvaluation) {
            return [];
        }

        return \App\Models\User::where('name', 'like', '%' . $this->shareSearch . '%')
            ->where('id', '!=', auth()->id()) // Don't show yourself
            ->where('id', '!=', $this->sharingEvaluation->created_by) // Don't show the owner
            ->whereNotIn('id', $this->sharingEvaluation->collaborators->pluck('id')) // Don't show existing collaborators
            ->take(5)
            ->get();
    }

    public function render()
    {
        // [NEW] Added ->with('creator') to make loading the author badge lightning fast
        $query = Evaluation::with('creator')->withCount('responses')->latest();

        if (auth()->user()->role?->role_name !== 'administrator') {
            $query->where('created_by', auth()->id());
        }

        $evaluations = $query->paginate(10);

        $layoutFile = auth()->user()->role?->role_name === 'administrator'
            ? 'layouts.madya-admin-deck'
            : 'layouts.madya-admin';

        // Pass the layout dynamically
        return view('livewire.admin.evaluation-list', compact('evaluations'))
            ->layout($layoutFile);
    }
}
