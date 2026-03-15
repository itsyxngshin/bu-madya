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

    public function render()
    {
        // [NEW] Added ->with('creator') to make loading the author badge lightning fast
        $query = Evaluation::with('creator')->latest();

        if (auth()->user()->role?->role_name !== 'administrator') {
            $query->where('created_by', auth()->id());
        }

        $evaluations = $query->paginate(10);

         $layoutFile = auth()->user()->role?->role_name === 'administrator' 
            ? 'layouts.madya-admin-deck' 
            : 'layouts.madya-admin'; 

        // Pass the layout dynamically
        return view('livewire.admin.evaluation-index', compact('evaluations'))
            ->layout($layoutFile);
    }
}
